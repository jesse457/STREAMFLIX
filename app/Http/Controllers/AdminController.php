<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertVideoForStreaming;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;


class AdminController extends Controller
{
    public function index()
    {
        return view('admin.upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'rating' => 'required|string|max:10',
            'type' => 'required|in:movie,series',
            'thumbnail' => 'required|image|max:10240', // 10MB
            'video' => 'required_if:type,movie|mimes:mp4,mov,avi,mkv|max:500000', // 500MB
            'episodes' => 'required_if:type,series|array',
            'episodes.*.title' => 'required_if:type,series|string|max:255',
            'episodes.*.season_number' => 'required_if:type,series|integer|min:1',
            'episodes.*.episode_number' => 'required_if:type,series|integer|min:1',
            'episodes.*.video' => 'required_if:type,series|mimes:mp4,mov,avi,mkv|max:500000',
        ]);

        // 1. Upload Thumbnail to S3
        $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 's3');

        // 2. Create Movie Record
        $movie = Movie::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'description' => $request->description ?? 'No description provided.',
            'thumbnail_url' => $thumbnailPath,
            'release_year' => $request->release_year,
            'rating' => $request->rating,
            'type' => $request->type,
            'views' => 0,
        ]);

        // 3. Handle Movie Video
        if ($request->type === 'movie' && $request->hasFile('video')) {
            $videoFile = $request->file('video');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $videoFile->getClientOriginalName());
            $videoFile->storeAs('temp', $filename, 'local');
            $movie->update(['video_url' => $filename]);
            ConvertVideoForStreaming::dispatch($movie);
        }

        // 4. Handle Series Episodes
        if ($request->type === 'series' && $request->has('episodes')) {
            foreach ($request->episodes as $index => $episodeData) {
                if (isset($episodeData['video'])) {
                    $videoFile = $episodeData['video'];
                    $filename = time() . '_ep' . ($index + 1) . '_' . preg_replace('/\s+/', '_', $videoFile->getClientOriginalName());
                    $videoFile->storeAs('temp', $filename, 'local');

                    $episode = $movie->episodes()->create([
                        'title' => $episodeData['title'],
                        'season_number' => $episodeData['season_number'],
                        'episode_number' => $episodeData['episode_number'],
                        'duration_minutes' => 0, // Will be updated if needed
                        'video_url' => $filename,
                    ]);

                    ConvertVideoForStreaming::dispatch($episode);
                }
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $movie->id,
                'message' => 'Upload successful! Processing started.',
            ]);
        }

        return redirect()->route('admin.upload')->with('success', 'Content added and processing started!');
    }
}
