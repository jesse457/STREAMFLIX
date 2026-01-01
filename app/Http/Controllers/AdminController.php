<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Jobs\ConvertVideoForStreaming;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mp4,mov,avi,mkv|max:500000', // 500MB
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $videoFile = $request->file('video');

        // Sanitize filename
        $filename = time() . '_' . preg_replace('/\s+/', '_', $videoFile->getClientOriginalName());

        // Save to 'local' disk in 'temp' folder.
        // IMPORTANT: storeAs returns the full path, e.g., "temp/1767233573_video.mp4"
        $videoPath = $videoFile->storeAs('temp', $filename, 'local');

        $video = Movie::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'description' => $request->description ?? 'No description provided.',
            'thumbnail_url' => 'https://via.placeholder.com/640x360?text=Processing...',
            'video_url' => $filename, // FIXED: Save the full path (temp/filename.ext), not just the filename
            'release_year' => date('Y'),
            'rating' => 'PG-13',
            'type' => 'movie'
        ]);

        // Dispatch Job
        ConvertVideoForStreaming::dispatch($video);

        return response()->json([
            'id' => $video->id,
            'message' => 'Upload successful! Transcoding started in background.'
        ]);
    }
}
