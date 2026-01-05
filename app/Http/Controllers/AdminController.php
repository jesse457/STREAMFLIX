<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertVideoForStreaming;
use App\Models\Movie;
use Illuminate\Http\Request;
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'rating' => 'required|string|max:10',
            'type' => 'required|in:movie,series',
            'thumbnail' => 'required|image|max:10240', // 10MB
            'video' => 'required_if:type,movie|mimes:mp4,mov,avi,mkv|max:500000', // 500MB
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

        // 3. Handle Video if type is movie
        if ($request->type === 'movie' && $request->hasFile('video')) {
            $videoFile = $request->file('video');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $videoFile->getClientOriginalName());
            
            // Step A: Save to local temp for processing
            $videoFile->storeAs('temp', $filename, 'local');
            
            // Step B: Update movie with the temp filename so the Job can find it
            $movie->update(['video_url' => $filename]);

            // Step C: Dispatch Job
            ConvertVideoForStreaming::dispatch($movie);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $movie->id,
                'message' => 'Upload successful! ' . ($request->type === 'movie' ? 'Processing started.' : 'Content added to library.'),
            ]);
        }

        return redirect()->route('browse.index')->with('success', 'Content added successfully!');
    }
}
