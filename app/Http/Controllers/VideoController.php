<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Jobs\ConvertVideoForStreaming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    // 1. Homepage / Gallery
    public function index()
    {
        // Get processed videos, latest first
        $videos = Video::where('processed', true)->latest()->get();
        return view('videos.index', compact('videos'));
    }

    // 2. Show Upload Form
    public function create()
    {
        return view('videos.create');
    }

    // 3. Handle Upload (Called via AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mp4,mov,avi,mkv|max:200000', // 200MB limit
            'title' => 'required|string|max:255',
        ]);

        $videoFile = $request->file('video');

        // Save to temp disk
        $filename = time() . '_' . preg_replace('/\s+/', '_', $videoFile->getClientOriginalName());
        $path = $videoFile->storeAs($filename, 'local');

        // Create DB entry
        $video = Video::create([
            'title' => $request->title,
            'original_filename' => $filename,
            'processed' => false // Default
        ]);

        // Dispatch Job
        ConvertVideoForStreaming::dispatch($video);

        return response()->json([
            'id' => $video->id,
            'message' => 'Upload successful! Processing started.'
        ]);
    }

    // 4. Watch Page
    public function show($id)
    {
        $video = Video::findOrFail($id);

        if (!$video->processed) {
             // In a real app, show a "Processing..." view
            return redirect()->route('videos.index')->with('error', 'Video is still processing.');
        }

        // Generate S3 URL (or CloudFront URL)
        $videoUrl = Storage::disk('s3')->url($video->stream_path);

        return view('videos.show', [
            'video' => $video,
            'videoUrl' => $videoUrl
        ]);
    }
}
