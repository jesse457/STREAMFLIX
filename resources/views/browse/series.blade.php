@extends('layouts.app')

@section('content')

<!-- 1. HERO SECTION (Featured Series) -->
@if($hero_series)
    <div class="relative w-full h-[65vh] md:h-[90vh]">
        <div class="absolute inset-0">
            <img src="{{ Storage::disk('s3')->url($hero_series->thumbnail_url) }}"
                 class="w-full h-full object-cover"
                 alt="{{ $hero_series->title }}">
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-transparent to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#141414] via-transparent to-transparent"></div>
        </div>

        <div class="relative h-full flex items-center px-4 md:px-12 pt-20">
            <div class="max-w-2xl space-y-4 md:space-y-6">
                <div class="flex items-center space-x-2">
                    <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-sm uppercase tracking-widest">Series</span>
                </div>

                <h1 class="text-4xl md:text-7xl font-black text-white drop-shadow-2xl uppercase leading-none">
                    {{ $hero_series->title }}
                </h1>

                <div class="flex items-center space-x-4 text-white font-semibold text-sm md:text-lg">
                    <span class="text-green-400">New Episodes</span>
                    <span>{{ $hero_series->release_year }}</span>
                    <span class="border border-gray-400 px-1 text-xs rounded">{{ $hero_series->rating }}</span>
                    <span>{{ $hero_series->episodes_count }} Episodes</span>
                </div>

                <p class="text-white text-base md:text-xl font-medium drop-shadow-md line-clamp-3 max-w-lg">
                    {{ $hero_series->description }}
                </p>

                <div class="flex items-center space-x-3 pt-2">
                    <!-- Play button usually starts S1:E1 or the last watched episode -->
                    <a href="{{ route('browse.watch', $hero_series) }}" class="flex items-center justify-center bg-white text-black hover:bg-white/80 px-6 md:px-8 py-2 md:py-3 rounded font-bold text-lg transition duration-200">
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
@else
    <div class="relative w-full h-[65vh] md:h-[90vh] flex items-center justify-center bg-[#141414]">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-black text-gray-800 uppercase">No Series Available</h1>
            <p class="text-gray-500 mt-4">Check back later for new content.</p>
        </div>
    </div>
@endif

<!-- 2. CONTENT ROWS -->
<div class="px-4 md:px-12 pb-20 mt-10 relative z-10 space-y-12">

    <!-- CONTINUE WATCHING SERIES -->
    @if($continue_watching->isNotEmpty())
        <div>
            <h2 class="text-lg md:text-xl font-bold text-white mb-2">Continue Watching for {{ Auth::user()->name }}</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
                @foreach($continue_watching as $progress)
                    <a href="{{ route('browse.watch.episode', ['movie' => $progress->movie_id, 'episode' => $progress->episode_id]) }}" class="group relative bg-netflix-dark rounded overflow-hidden transition-all duration-300 hover:scale-105">
                        <div class="aspect-video w-full relative">
                            <img src="{{ Storage::disk('s3')->url($progress->movie->thumbnail_url) }}" class="w-full h-full object-cover">
                            <div class="absolute bottom-2 left-2 bg-black/70 text-white text-[10px] px-2 py-1 rounded">
                                S{{ $progress->episode->season_number }}:E{{ $progress->episode->episode_number }}
                            </div>
                        </div>
                        <!-- Progress Bar -->
                        <div class="bg-gray-700 h-1 w-full">
                            <div class="bg-netflix-red h-1" style="width: {{ ($progress->progress_seconds / ($progress->episode->duration_minutes * 60)) * 100 }}%"></div>
                        </div>
                        <div class="p-2 bg-[#181818]">
                            <h3 class="text-white text-xs font-bold truncate">{{ $progress->movie->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- TRENDING SERIES -->
    <div>
        <h2 class="text-lg md:text-xl font-bold text-white mb-2">Trending Series</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
            @foreach($trending_series as $series)
                <div class="group relative transition-all duration-300 hover:scale-110 hover:z-30 cursor-pointer">
                    <img src="{{ Storage::disk('s3')->url($series->thumbnail_url) }}" class="rounded-sm w-full h-auto">
                    <!-- Hover Info Overlay -->
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex flex-col justify-end p-2 transition-opacity">
                        <p class="text-white font-bold text-xs">{{ $series->title }}</p>
                        <p class="text-green-400 text-[10px]">{{ $series->episodes_count }} Episodes</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- GENRE ROW: TV COMEDIES -->
    @if($comedy_series->isNotEmpty())
    <div>
        <h2 class="text-lg md:text-xl font-bold text-white mb-2">TV Comedies</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
            @foreach($comedy_series as $series)
                <x-movie-card :movie="$series" />
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
