<?php

namespace App\Jobs;

use App\Models\Movie;
use App\Models\Video;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log as FacadesLog;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $model;
    public $timeout = 3600; // 1 hour
    public $tries = 1;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function handle()
    {
        $modelName = strtolower(class_basename($this->model));
        $id = $this->model->id;

        // Define Bitrates
        $lowBitrate = (new X264)->setKiloBitrate(250);
        $midBitrate = (new X264)->setKiloBitrate(500);
        $highBitrate = (new X264)->setKiloBitrate(1000);

        // Step A: Generate Thumbnail
        FFMpeg::fromDisk('local')
            ->open('temp/'.$this->model->video_url)
            ->getFrameFromSeconds(10)
            ->export()
            ->toDisk('s3')
            ->save("thumbnails/{$modelName}_{$id}/thumbnail.jpg");

        // Step B: Export for HLS
        FFMpeg::fromDisk('local')
            ->open('temp/'.$this->model->video_url)
            ->exportForHLS()
            ->setSegmentLength(10)
            ->addFormat($lowBitrate)
            ->addFormat($midBitrate)
            ->addFormat($highBitrate)
            ->toDisk('s3')
            ->save("videos/{$modelName}_{$id}/playlist.m3u8");

        // Step C: Update Database
        // For episodes, we don't necessarily update a thumbnail_url field on the episode itself 
        // unless it's designed to have one. The migration for episodes doesn't have thumbnail_url.
        // It only has video_url.
        
        $updateData = [
            'video_url' => "videos/{$modelName}_{$id}/playlist.m3u8",
        ];

        // If the model has thumbnail_url column (like Movie), update it
        if (isset($this->model->thumbnail_url)) {
            $updateData['thumbnail_url'] = "thumbnails/{$modelName}_{$id}/thumbnail.jpg";
        }

        $this->model->update($updateData);

        FacadesLog::info("{$modelName} ID {$id} processed and saved to S3.");
    }
}
