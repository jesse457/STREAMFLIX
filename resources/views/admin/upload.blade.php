@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-24 bg-cover bg-center" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.9)), url('https://assets.nflxext.com/ffe/siteui/vlv3/f841d4c7-10e1-40af-bcae-07a3f8dc141a/f6d7434e-d6de-4185-a6d4-c77a2d08737b/US-en-20220502-popsignuptwoweeks-perspective_alpha_website_medium.jpg');">

        <div class="w-full max-w-lg bg-black/80 backdrop-blur-sm p-8 md:p-12 rounded-lg border border-transparent shadow-2xl" x-data="{ type: '{{ old('type', 'movie') }}', fileName: '' }">

            <h2 class="text-3xl font-bold text-white mb-8">Add to Library</h2>

            <!-- Standard Blade Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 bg-netflix-red/20 border border-netflix-red text-white p-4 rounded text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Type Selection -->
                <div class="mb-6">
                    <label class="block text-gray-400 text-sm font-bold mb-2">Content Type</label>
                    <input type="hidden" name="type" :value="type">
                    <div class="flex space-x-4">
                        <button type="button" @click="type = 'movie'"
                            :class="type === 'movie' ? 'bg-netflix-red text-white' : 'bg-gray-700 text-gray-300'"
                            class="flex-1 py-3 rounded font-bold transition">
                            Movie
                        </button>
                        <button type="button" @click="type = 'series'"
                            :class="type === 'series' ? 'bg-netflix-red text-white' : 'bg-gray-700 text-gray-300'"
                            class="flex-1 py-3 rounded font-bold transition">
                            Series
                        </button>
                    </div>
                </div>

                <!-- Title -->
                <div class="mb-6 relative">
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        class="block w-full px-4 py-4 rounded bg-[#333] text-white border-none focus:ring-0 focus:bg-[#454545] peer placeholder-transparent transition"
                        placeholder="Title" required>
                    <label for="title"
                        class="absolute text-gray-400 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3">
                        Title
                    </label>
                </div>

                <!-- Description -->
                <div class="mb-6 relative">
                    <textarea name="description" id="description" rows="3"
                        class="block w-full px-4 py-4 rounded bg-[#333] text-white border-none focus:ring-0 focus:bg-[#454545] peer placeholder-transparent transition"
                        placeholder="Description">{{ old('description') }}</textarea>
                    <label for="description"
                        class="absolute text-gray-400 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3">
                        Description
                    </label>
                </div>

                <!-- Metadata Row -->
                <div class="flex space-x-4 mb-6">
                    <div class="w-1/2 relative">
                        <input type="number" name="release_year" id="release_year" value="{{ old('release_year', date('Y')) }}"
                            class="block w-full px-4 py-4 rounded bg-[#333] text-white border-none focus:ring-0 focus:bg-[#454545] peer placeholder-transparent transition"
                            placeholder="Year" required>
                        <label for="release_year"
                            class="absolute text-gray-400 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3">
                            Year
                        </label>
                    </div>
                    <div class="w-1/2 relative">
                        <input type="text" name="rating" id="rating" value="{{ old('rating') }}"
                            class="block w-full px-4 py-4 rounded bg-[#333] text-white border-none focus:ring-0 focus:bg-[#454545] peer placeholder-transparent transition"
                            placeholder="Rating (e.g. PG-13)" required>
                        <label for="rating"
                            class="absolute text-gray-400 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3">
                            Rating
                        </label>
                    </div>
                </div>

                <!-- Thumbnail Upload -->
                <div class="mb-6">
                    <label class="block text-gray-400 text-sm font-bold mb-2">Thumbnail / Poster</label>
                    <input type="file" name="thumbnail" accept="image/*" required
                         class="block w-full text-sm text-gray-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-[#333] file:text-white
                                hover:file:bg-[#444]">
                </div>

                <!-- File Drop Zone -->
                <div class="mb-8" x-show="type === 'movie'" x-transition>
                    <label class="block text-gray-400 text-sm font-bold mb-2">Video File</label>
                    <div class="relative w-full h-40 border-2 border-dashed border-gray-600 rounded-lg bg-[#181818] hover:bg-[#202020] hover:border-white transition flex flex-col items-center justify-center cursor-pointer group"
                        @click="$refs.fileInput.click()">

                        <input type="file" name="video" x-ref="fileInput" @change="fileName = $event.target.files[0].name" class="hidden" accept="video/*">

                        <div x-show="!fileName" class="text-center p-4">
                            <svg class="w-10 h-10 mx-auto text-gray-500 group-hover:text-white transition mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="text-sm text-gray-400">Click to browse video file</p>
                        </div>

                        <div x-show="fileName" class="text-center p-4 w-full">
                            <div class="flex items-center justify-center space-x-2 text-green-500 mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-bold text-sm truncate max-w-[200px]" x-text="fileName"></span>
                            </div>
                            <span class="text-xs text-netflix-red hover:underline uppercase font-bold tracking-wide">Change File</span>
                        </div>
                    </div>
                </div>

                <div x-data="{ isSubmitting: false }">
                    <button type="submit" @click="isSubmitting = true"
                        class="w-full bg-netflix-red hover:bg-netflix-hover text-white font-bold py-4 rounded transition duration-200 text-lg flex items-center justify-center">
                        <span x-show="!isSubmitting">Upload Video</span>
                        <span x-show="isSubmitting" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Uploading...
                        </span>
                    </button>
                    <p x-show="isSubmitting" class="text-xs text-gray-400 mt-2 text-center">Large files may take a minute. Please do not close the window.</p>
                </div>
            </form>
        </div>
    </div>
@endsection
