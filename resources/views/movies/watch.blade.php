<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watching {{ $movie->title }}</title>

    <!-- Load Compiled Assets via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])



    <style>
        body { background-color: #000; margin: 0; overflow: hidden; }
        .video-overlay-top {
            position: absolute; top: 0; left: 0; right: 0; padding: 40px;
            background: linear-gradient(to bottom, rgba(0,0,0,0.8) 0%, transparent 100%);
            z-index: 10; transition: opacity 0.3s; display: flex; align-items: center;
        }
        .vjs-user-inactive .video-overlay-top { opacity: 0; }
    </style>
</head>
<body>

    <div class="video-overlay-top text-white">
        <a href="javascript:history.back()" class="mr-5 hover:scale-110 transition-transform">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="text-2xl font-bold">{{ $movie->title }}</h1>
    </div>

    <video id="netflix-player" class="video-js vjs-netflix vjs-big-play-centered" controls preload="auto" poster="{{ $movie->thumbnail_url }}">
        <source src="{{ Storage::disk('s3')->url($movie->video_url) }}" type="application/x-mpegURL">
    </video>

    <script>
        // Use a DOMContentLoaded listener to ensure Vite has loaded Video.js
        document.addEventListener('DOMContentLoaded', function() {
            var player = videojs('netflix-player', {
                autoplay: false,
                fluid: false,
                html5: {
                    vhs: { overrideNative: true },
                    nativeVideoTracks: false,
                    nativeAudioTracks: false,
                    nativeTextTracks: false
                }
            });

            player.ready(function() {
                // Initialize the quality selector
                player.httpSourceSelector();
            });

            // Shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.code === 'Space') { e.preventDefault(); player.paused() ? player.play() : player.pause(); }
                if (e.code === 'ArrowRight') player.currentTime(player.currentTime() + 10);
                if (e.code === 'ArrowLeft') player.currentTime(player.currentTime() - 10);
            });
        });
    </script>
</body>
</html>
