<?php

namespace App\Jobs;

use App\Models\Movie;
use App\Models\Video;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Container\Attributes\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log as FacadesLog;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;

    public function __construct(Movie $video)
    {
        $this->video = $video;
    }

    public function handle()
    {
        // Define Bitrates (Low, Mid, High quality)
        $lowBitrate  = (new X264)->setKiloBitrate(250);
        $midBitrate  = (new X264)->setKiloBitrate(500);
        $highBitrate = (new X264)->setKiloBitrate(1000);
        // We apply this exactly to your setup:
        FFMpeg::fromDisk('local')
            ->open('temp/' . $this->video->video_url)
            ->getFrameFromSeconds(10)
            ->export()
            ->toDisk('s3') // Directly save to S3 as per docs
            ->save("thumbnails/{$this->video->id}/thumbnail.jpg");
        // Open the local temp file
        FFMpeg::fromDisk('local')
            ->open('temp/' . $this->video->video_url)

            // Export for HLS
            ->exportForHLS()
            ->setSegmentLength(10) // 10 second chunks
            ->addFormat($lowBitrate)
            ->addFormat($midBitrate)
            ->addFormat($highBitrate)

            // Save to S3
            ->toDisk('s3')
            ->save("videos/{$this->video->id}/playlist.m3u8");

        // Update Database
        $this->video->update([
            'thumbnail_url' => "thumbnails/{$this->video->id}/thumbnail.jpg",
            'video_url' => "videos/{$this->video->id}/playlist.m3u8"
        ]);
FacadesLog::info("Video ID {$this->video->id} processed and saved to S3.");
        // Optional: Delete local temp file
        // Storage::disk('local')->delete('temp/' . $this->video->original_filename);
    }
}
