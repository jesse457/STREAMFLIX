@extends('layouts.app')

@section('content')
<!-- Plugin CSS -->
<link href="https://unpkg.com/videojs-hls-quality-selector/dist/videojs-hls-quality-selector.css" rel="stylesheet">

<style>
    /* Styling the Quality Selector Menu */
    .vjs-menu-button-popup .vjs-menu {
        background-color: #141414e6;
        border: 1px solid #333;
        width: 10em;
    }
    .vjs-menu-item {
        color: #ccc;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
    }
    .vjs-menu-item:hover {
        background-color: #333;
        color: white;
    }
    .vjs-menu-item.vjs-selected {
        background-color: #E50914; /* Netflix Red */
        color: white;
    }
</style>

<!-- Full Width Player Container -->
<div class="w-full bg-black pt-[64px]"> <!-- Offset Navbar -->
    <div class="w-full max-h-[80vh] bg-black flex justify-center">
        <!-- 16:9 Aspect Ratio Container controlled by max-height -->
        <div class="relative w-full max-w-[1600px]" style="aspect-ratio: 16/9;">
            <video
                id="my-video"
                class="video-js vjs-fill vjs-big-play-centered"
                controls
                preload="auto"
                poster="https://via.placeholder.com/1920x1080/000000/333333?text=Video+Loading"
                data-setup='{"fluid": true}'
            >
                <source src="{{ $videoUrl }}" type="application/x-mpegURL">
                <p class="vjs-no-js">To view this video please enable JavaScript.</p>
            </video>
        </div>
    </div>
</div>

<!-- Info Section -->
<div class="px-4 md:px-12 py-8 max-w-7xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <!-- Left: Details -->
        <div class="lg:col-span-2 text-white">
            <div class="flex items-center space-x-3 mb-4 text-gray-400 font-bold uppercase tracking-wide text-sm">
                <span>{{ $video->created_at->format('Y') }}</span>
                <span class="border border-gray-500 px-1 text-white">16+</span>
                <span>1 Season</span>
                <span class="border border-gray-500 px-1 text-xs rounded">HD</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-black mb-4">{{ $video->title }}</h1>

            <div class="flex items-center space-x-4 mb-6">
                <span class="text-green-400 font-bold">98% Match</span>
                <span class="text-white font-semibold">#1 in Movies Today</span>
            </div>

            <p class="text-lg text-white leading-relaxed mb-6 font-light">
                This is a description placeholder. The video is streamed via AWS S3 using HLS adaptive bitrate streaming.
                Experience the clarity and speed. Hover over the player gear icon to switch qualities.
            </p>

            <div class="flex space-x-4">
                <button class="flex flex-col items-center group text-gray-400 hover:text-white transition">
                    <svg class="w-8 h-8 mb-1 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span class="text-xs uppercase font-bold tracking-wide">My List</span>
                </button>
                <button class="flex flex-col items-center group text-gray-400 hover:text-white transition">
                    <svg class="w-8 h-8 mb-1 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                    <span class="text-xs uppercase font-bold tracking-wide">Rate</span>
                </button>
                <button class="flex flex-col items-center group text-gray-400 hover:text-white transition">
                    <svg class="w-8 h-8 mb-1 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                    <span class="text-xs uppercase font-bold tracking-wide">Share</span>
                </button>
            </div>
        </div>

        <!-- Right: Meta details -->
        <div class="text-sm text-gray-400 leading-7">
            <div><span class="text-gray-500">Cast:</span> <span class="text-white hover:underline cursor-pointer">Actor Name</span>, <span class="text-white hover:underline cursor-pointer">Another Actor</span></div>
            <div><span class="text-gray-500">Genres:</span> <span class="text-white hover:underline cursor-pointer">Action</span>, <span class="text-white hover:underline cursor-pointer">Sci-Fi</span></div>
            <div><span class="text-gray-500">This movie is:</span> <span class="text-white">Mind-Bending, Dark</span></div>
        </div>
    </div>

    <!-- More Like This Grid -->
    <div class="mt-12 border-t border-gray-800 pt-8">
        <h3 class="text-2xl font-bold text-white mb-6">More Like This</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
             <!-- Static placeholders for visual effect -->
             @for($i=0; $i<4; $i++)
             <div class="bg-netflix-dark rounded overflow-hidden cursor-pointer hover:bg-gray-800 transition">
                <div class="aspect-w-16 aspect-h-9 bg-gray-700 relative group">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                        <div class="w-10 h-10 rounded-full border-2 border-white flex items-center justify-center bg-black/50">
                            <svg class="w-5 h-5 text-white" fill="white" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex justify-between text-gray-400 text-sm mb-2">
                        <span>90% Match</span>
                        <span class="border border-gray-600 px-1 text-xs">HD</span>
                    </div>
                    <p class="text-gray-300 text-sm line-clamp-2">A similar video you might like based on the algorithm.</p>
                </div>
             </div>
             @endfor
        </div>
    </div>
</div>

<!-- Video Scripts -->
<script src="https://vjs.zencdn.net/7.20.3/video.min.js"></script>
<script src="https://unpkg.com/videojs-contrib-quality-levels@2.1.0/dist/videojs-contrib-quality-levels.min.js"></script>
<script src="https://unpkg.com/videojs-hls-quality-selector@1.1.0/dist/videojs-hls-quality-selector.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var player = videojs('my-video');
        player.hlsQualitySelector({ displayCurrentQuality: true });
    });
</script>
@endsection
