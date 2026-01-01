@props(['movie'])

<a href="{{ url('/watch/' . $movie->id) }}" class="group relative bg-[#181818] rounded-sm overflow-hidden transition-all duration-300 hover:scale-110 hover:z-50 shadow-lg hover:shadow-black/70 delay-100">

    <!-- Thumbnail -->
    <div class="aspect-video w-full relative">
        <img src="{{ $movie->thumbnail_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
    </div>

    <!-- Content (Hidden by default, shown on hover like Netflix) -->
    <div class="p-4 absolute bottom-0 left-0 right-0 bg-[#181818] opacity-0 group-hover:opacity-100 transition-opacity duration-300 translate-y-full group-hover:translate-y-0">

        <!-- Action Row -->
        <div class="flex items-center gap-3 mb-3">
            <button class="w-8 h-8 bg-white rounded-full flex items-center justify-center hover:bg-gray-200 text-black">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </button>
            <button class="w-8 h-8 border-2 border-gray-400 rounded-full flex items-center justify-center hover:border-white text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </button>
            <button class="ml-auto w-8 h-8 border-2 border-gray-400 rounded-full flex items-center justify-center hover:border-white text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
        </div>

        <!-- Metadata -->
        <div class="flex items-center gap-2 text-[10px] font-semibold text-gray-400 mb-1">
            <span class="text-green-400">95% Match</span>
            <span class="border border-gray-500 px-1">{{ $movie->rating }}</span>
            <span>{{ $movie->release_year }}</span>
        </div>

        <!-- Genres -->
        <div class="flex items-center gap-1 text-[10px] text-white">
            @foreach($movie->genres->take(3) as $genre)
                <span>{{ $genre->name }}</span>
                @if(!$loop->last) <span class="text-gray-600">&bull;</span> @endif
            @endforeach
        </div>
    </div>
</a>
