@extends('layouts.profile')

@section('content')
<div class="flex min-h-screen bg-[#141414] text-white" 
    x-data="uploadForm()"
    x-cloak>
    
    <!-- Sidebar (Unchanged) -->
    <aside class="w-64 bg-black/50 backdrop-blur-md border-r border-white/10 hidden lg:flex flex-col fixed h-full z-40">
        <div class="p-8">
            <h1 class="text-2xl font-black text-netflix-red tracking-tighter uppercase">STREAMFLIX</h1>
            <p class="text-gray-500 text-xs mt-1 uppercase tracking-widest font-bold">Admin Console</p>
        </div>
        {{-- <nav class="flex-grow px-4 space-y-2 mt-4">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-white transition group">
                <svg class="w-5 h-5 group-hover:text-netflix-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-medium">Dashboard</span>
            </a> --}}
            <a href="{{ route('admin.upload') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-white/5 text-white border border-white/10 transition group">
                <svg class="w-5 h-5 text-netflix-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <span class="font-medium">Upload Content</span>
            </a>
        </nav>
        <div class="p-6 border-t border-white/10">
          <div class="flex items-center space-x-4">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/0b/Netflix-avatar.png" class="w-10 h-10 rounded shadow-lg" alt="Admin">
                <div>
                    <p class="text-sm font-bold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">Administrator</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-grow lg:ml-64 p-4 md:p-8 lg:p-12">
        <!-- Top Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-12">
            <div>
                <h2 class="text-4xl font-black tracking-tight mb-2">Upload Content</h2>
                <p class="text-gray-400">Add a new movie or series to your library.</p>
            </div>
            <div class="flex gap-3">
                <button type="button" @click="type = 'movie'" 
                    :class="type === 'movie' ? 'bg-netflix-red text-white shadow-[0_0_20px_rgba(229,9,20,0.3)]' : 'bg-white/5 text-gray-400 hover:bg-white/10'"
                    class="px-6 py-2.5 rounded-full font-bold transition flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>
                    <span>Movie</span>
                </button>
                <button type="button" @click="type = 'series'"
                    :class="type === 'series' ? 'bg-netflix-red text-white shadow-[0_0_20px_rgba(229,9,20,0.3)]' : 'bg-white/5 text-gray-400 hover:bg-white/10'"
                    class="px-6 py-2.5 rounded-full font-bold transition flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>
                    <span>Series</span>
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-green-500/10 border border-green-500/50 text-green-500 rounded-xl flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 p-6 bg-netflix-red/10 border border-netflix-red/50 text-white rounded-xl">
                <div class="flex items-center space-x-3 mb-4">
                    <svg class="w-6 h-6 text-netflix-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="font-bold">Please correct the following:</span>
                </div>
                <ul class="list-disc list-inside text-sm text-gray-300 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data" 
              class="space-y-8 max-w-5xl"
              @submit.prevent="submitHandler" 
              x-ref="form">
            @csrf
            <input type="hidden" name="type" :value="type">

            <!-- Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Poster and Basic Metadata -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-6">Visual Assets</h3>
                        
                        <!-- Thumbnail Input -->
                        <div class="relative group aspect-[2/3] bg-black rounded-xl overflow-hidden border-2 border-dashed border-white/20 hover:border-netflix-red transition-colors cursor-pointer"
                             @click="$refs.thumbnailInput.click()">
                            <input type="file" name="thumbnail" x-ref="thumbnailInput" @change="previewThumbnail" class="hidden" accept="image/*">
                            
                            <!-- Preview Image -->
                            <img x-show="thumbnailPreview" :src="thumbnailPreview" class="w-full h-full object-cover absolute inset-0 z-10">
                            
                            <!-- Placeholder -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-4 z-0">
                                <svg class="w-12 h-12 text-gray-500 mb-2 group-hover:text-netflix-red transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-xs font-bold text-gray-400">Click to upload poster</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-6">Metadata</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">Release Year</label>
                                <input type="number" name="release_year" value="{{ old('release_year', date('Y')) }}" class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2.5 focus:border-netflix-red transition outline-none text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">Maturity Rating</label>
                                <select name="rating" class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2.5 focus:border-netflix-red transition outline-none text-white">
                                    <option value="G">G - General</option>
                                    <option value="PG">PG - Parental Guidance</option>
                                    <option value="PG-13">PG-13 - Teenagers</option>
                                    <option value="R">R - Restricted</option>
                                    <option value="TV-MA">TV-MA - Mature Audiences</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Details and Content -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-8">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-6">General Information</h3>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">Title</label>
                                <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. The Witcher" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-4 text-xl font-bold focus:border-netflix-red transition outline-none placeholder:text-gray-700 text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 mb-1">Synopsis</label>
                                <textarea name="description" rows="5" placeholder="Tell us about this content..." class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-4 focus:border-netflix-red transition outline-none placeholder:text-gray-700 text-white">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Content Upload Section (Movie) -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-8 shadow-xl" x-show="type === 'movie'" x-transition>
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">Video Source</h3>
                            <span class="text-[10px] bg-netflix-red px-2 py-0.5 rounded text-white font-bold">4K READY</span>
                        </div>
                        
                        <div class="relative w-full h-48 border-2 border-dashed border-white/10 rounded-2xl bg-black/40 hover:bg-black/60 hover:border-netflix-red transition-all cursor-pointer group flex flex-col items-center justify-center overflow-hidden"
                             @click="$refs.mainVideoInput.click()">
                            <input type="file" name="video" x-ref="mainVideoInput" @change="mainFileName = $event.target.files[0].name" class="hidden" accept="video/*">
                            
                            <div x-show="!mainFileName" class="text-center group-hover:scale-105 transition-transform duration-300">
                                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-netflix-red transition-colors">
                                    <svg class="w-8 h-8 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-300">Choose Video File</p>
                                <p class="text-xs text-gray-500 mt-1">MP4, MKV or MOV</p>
                            </div>
                            
                            <div x-show="mainFileName" class="text-center p-8 w-full">
                                <div class="flex items-center justify-center space-x-3 text-green-500 mb-2">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    <span class="text-xl font-black truncate max-w-[80%]" x-text="mainFileName"></span>
                                </div>
                                <span class="text-xs font-bold text-netflix-red underline">Change video</span>
                            </div>
                        </div>
                    </div>

                    <!-- Episodes Section for Series (Unchanged Logic, mostly UI) -->
                    <div class="space-y-4" x-show="type === 'series'" x-transition>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">Series Episodes</h3>
                            <button type="button" @click="addEpisode()" class="text-xs bg-white text-black font-bold px-4 py-1.5 rounded hover:bg-gray-200 transition">
                                Add Episode
                            </button>
                        </div>

                        <template x-for="(episode, index) in episodes" :key="index">
                            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 relative group">
                                <button type="button" @click="removeEpisode(index)" class="absolute top-4 right-4 text-gray-600 hover:text-netflix-red opacity-0 group-hover:opacity-100 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                                    <!-- Inputs for Episode -->
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">SN</label>
                                        <input type="number" :name="'episodes['+index+'][season_number]'" x-model="episode.season_number" class="w-full bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-center font-bold text-white outline-none focus:border-netflix-red">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">EP</label>
                                        <input type="number" :name="'episodes['+index+'][episode_number]'" x-model="episode.episode_number" class="w-full bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-center font-bold text-white outline-none focus:border-netflix-red">
                                    </div>
                                    <div class="md:col-span-5">
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Episode Title</label>
                                        <input type="text" :name="'episodes['+index+'][title]'" x-model="episode.title" class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2 outline-none focus:border-netflix-red transition text-white">
                                    </div>
                                    <div class="md:col-span-3">
                                        <div class="relative">
                                            <input type="file" :name="'episodes['+index+'][video]'" class="hidden" :id="'ep-video-'+index" accept="video/*" @change="episode.fileName = $event.target.files[0].name">
                                            <button type="button" @click="document.getElementById('ep-video-'+index).click()" 
                                                class="w-full text-xs font-bold py-2.5 px-4 rounded-lg border border-dashed transition truncate"
                                                :class="episode.fileName ? 'border-green-500/50 bg-green-500/5 text-green-500' : 'border-white/20 bg-white/5 text-gray-400 hover:border-white/40'">
                                                <span x-text="episode.fileName ? 'Selected' : 'Select Video'"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="pt-8 border-t border-white/10 flex items-center justify-between">
                <p class="text-sm text-gray-500 italic max-w-sm">
                    Warning: Large files (500MB+) may take several minutes to upload. Do not close this tab.
                </p>
                <button type="submit" 
                        class="bg-netflix-red hover:bg-netflix-hover text-white font-black px-12 py-5 rounded-xl shadow-2xl flex items-center space-x-4 group transition-all transform hover:scale-105 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="isSubmitting">
                    <span class="text-xl" x-text="isSubmitting ? 'Uploading (' + progress + '%)' : 'Publish Content'"></span>
                    <svg x-show="!isSubmitting" class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    <!-- Spinner -->
                    <svg x-show="isSubmitting" class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
            </div>
        </form>
    </main>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('uploadForm', () => ({
            type: '{{ old('type', 'movie') }}',
            episodes: [{ title: '', season_number: 1, episode_number: 1, fileName: '' }],
            isSubmitting: false,
            progress: 0,
            mainFileName: '',
            thumbnailPreview: null,

            addEpisode() {
                this.episodes.push({ title: '', season_number: 1, episode_number: this.episodes.length + 1, fileName: '' });
            },
            removeEpisode(index) {
                this.episodes.splice(index, 1);
            },
            previewThumbnail(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.thumbnailPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },
            submitHandler() {
                // Client-side Validation to prevent hanging
                if(this.type === 'movie' && !this.mainFileName) {
                    alert('Please select a movie file.');
                    return;
                }
                
                this.isSubmitting = true;
                this.$refs.form.submit(); // Actually submit the form
                
                // Simulating progress since standard form submit doesn't give feedback
                // Note: For real progress bars, you need AJAX/Axios, but this 
                // keeps the UI responsive for standard POST.
                let interval = setInterval(() => {
                    if(this.progress < 90) this.progress += 5;
                }, 500);
            }
        }));
    });
</script>

<style>
    [x-cloak] { display: none !important; }
    .bg-netflix-red { background-color: #E50914; }
    .bg-netflix-hover { background-color: #f40612; }
    .text-netflix-red { color: #E50914; }
</style>
@endsection