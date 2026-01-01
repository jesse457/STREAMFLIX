@extends('layouts.app')

@section('content')

<!-- 1. HERO SECTION -->
@if($hero_movie)
    <div class="relative w-full h-[65vh] md:h-[90vh]">
        <!-- Background Image -->
        <div class="absolute inset-0">
            <img src="{{Storage::disk('s3')->url($hero_movie->thumbnail_url)}}"
                 class="w-full h-full object-cover"
                 alt="{{ $hero_movie->title }}">

            <!-- Vignette Overlays (Top, Bottom, Left) -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-transparent to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#141414] via-transparent to-transparent"></div>
        </div>

        <!-- Hero Content -->
        <div class="relative h-full flex items-center px-4 md:px-12 pt-20">
            <div class="max-w-2xl space-y-4 md:space-y-6">
                <!-- Title -->
                <h1 class="text-4xl md:text-7xl font-black text-white drop-shadow-2xl uppercase leading-none">
                    {{ $hero_movie->title }}
                </h1>

                <!-- Meta Info -->
                <div class="flex items-center space-x-4 text-white font-semibold text-sm md:text-lg drop-shadow-md">
                    <span class="text-green-400">98% Match</span>
                    <span>{{ $hero_movie->release_year }}</span>
                    <span class="border border-gray-400 px-1 text-xs rounded">{{ $hero_movie->rating }}</span>
                </div>

                <!-- Description -->
                <p class="text-white text-base md:text-xl font-medium drop-shadow-md line-clamp-3 text-shadow-lg max-w-lg">
                    {{ $hero_movie->description }}
                </p>

                <!-- Buttons -->
                <div class="flex items-center space-x-3 pt-2">
                    <!-- Link to Play Route -->
                    <a href="{{ url('/watch/' . $hero_movie->id) }}" class="flex items-center justify-center bg-white text-black hover:bg-white/80 px-6 md:px-8 py-2 md:py-3 rounded font-bold text-lg transition duration-200">
                        <svg class="w-7 h-7 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        Play
                    </a>
                    <button class="flex items-center justify-center bg-gray-500/40 hover:bg-gray-500/30 text-white px-6 md:px-8 py-2 md:py-3 rounded font-bold text-lg transition duration-200 backdrop-blur-md">
                        More Info
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- 2. CONTENT ROWS CONTAINER -->
<div class="px-4 md:px-12 pb-20 -mt-24 relative z-10 space-y-12">

    <!-- ROW 1: CONTINUE WATCHING (Only if exists) -->
    @if($continue_watching->isNotEmpty())
        <div>
            <h2 class="text-lg md:text-xl font-bold text-white mb-2 hover:text-gray-300 cursor-pointer transition">
                Continue Watching
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2">
                @foreach($continue_watching as $progress)
                    <a href="{{ url('/watch/' . $progress->movie->id) }}" class="group relative bg-netflix-dark rounded overflow-hidden cursor-pointer transition-all duration-300 hover:z-20 hover:scale-105">
                        <!-- Thumbnail -->
                        <div class="aspect-video w-full relative">
                            <img src="{{Storage::disk('s3')->url($progress->movie->thumbnail_url)}}" class="w-full h-full object-cover">

                            <!-- Play Icon on Hover -->
                            <div class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition duration-300">
                                <svg class="w-12 h-12 text-white bg-black/50 rounded-full p-2" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>

                        <!-- Progress Bar (The Key Netflix Feature) -->
                        <div class="bg-gray-700 h-1 w-full">
                            @php
                                // Calculate percentage based on duration
                                // Assume duration is in minutes, progress in seconds
                                $percent = ($progress->progress_seconds / ($progress->movie->duration ?? 120 * 60)) * 100;
                            @endphp
                            <div class="bg-netflix-red h-1" style="width: {{ $percent }}%"></div>
                        </div>

                        <div class="p-3 bg-netflix-dark">
                            <h3 class="text-gray-200 text-sm font-bold truncate">{{ $progress->movie->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ROW 2: TRENDING NOW -->
    <div>
        <h2 class="text-lg md:text-xl font-bold text-white mb-2">Trending Now</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 md:gap-4">
            @foreach($trending as $movie)
                <x-movie-card :movie="$movie" />
            @endforeach
        </div>
    </div>

    <!-- ROW 3: ACTION MOVIES -->
    @if($action_movies->isNotEmpty())
    <div>
        <h2 class="text-lg md:text-xl font-bold text-white mb-2">Action Movies</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 md:gap-4">
            @foreach($action_movies as $movie)
                <x-movie-card :movie="$movie" />
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
