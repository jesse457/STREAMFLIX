<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Netflix Style Player</title>
    <!-- Tailwind CSS -->
 @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #141414; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }

        /* Netflix Red */
        .netflix-red { color: #E50914; }
        .bg-netflix-red { background-color: #E50914; }

        /* Range Input Styling */
        input[type=range] { -webkit-appearance: none; background: transparent; }
        input[type=range]::-webkit-slider-thumb { -webkit-appearance: none; height: 0; width: 0; }

        /* Scrubber Head */
        .scrubber-head { transform: scale(0); transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1); }
        .group\/progress:hover .scrubber-head, .scrubber-dragging .scrubber-head { transform: scale(1); }

        /* Spinner */
        .spinner {
            width: 50px; height: 50px;
            border: 4px solid rgba(229, 9, 20, 0.3);
            border-radius: 50%;
            border-top-color: #E50914;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Ripple Animation */
        .ripple-icon { animation: ping-fade 0.8s cubic-bezier(0, 0, 0.2, 1) forwards; }
        @keyframes ping-fade {hl
            0% { transform: scale(0.5); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: scale(1.5); opacity: 0; }
        }
    </style>

    <script>
        function netflixPlayer() {
            return {
                hls: null,
                // Ensure quotes are handled correctly for blade
                videoSrc: "{{ Storage::disk('s3')->url($movie->video_url) }}",
                poster: "{{ Storage::disk('s3')->url($movie->thumbnail_url) }}",
                movieTitle: "{{ $movie->title ?? 'Stranger Things' }}",
                episodeTitle: "{{ $movie->episode_title ?? 'S1:E1' }}",

                // State
                isPlaying: false,
                isLoading: true,
                progress: 0,
                buffered: 0,
                volume: 1,
                muted: false,
                currentTime: 0,
                duration: 0,
                playbackRate: 1,

                // UI State
                showControls: true,
                isSettingsOpen: false,
                settingsMenu: 'main',
                timer: null,
                isFullscreen: false,

                // Data
                qualities: [],
                currentQuality: -1,

                // Double Tap
                lastTap: 0,
                showDoubleTapOverlay: null,

                initPlayer() {
                    const video = this.$refs.video;
                    if (Hls.isSupported()) {
                        this.hls = new Hls();
                        this.hls.loadSource(this.videoSrc);
                        this.hls.attachMedia(video);
                        this.hls.on(Hls.Events.MANIFEST_PARSED, (event, data) => {
                            this.qualities = data.levels.map((l, index) => ({ id: index, height: l.height, label: l.height + 'p' })).reverse();
                            this.isLoading = false;
                        });
                        this.hls.on(Hls.Events.ERROR, () => this.isLoading = true);
                    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                        video.src = this.videoSrc;
                        this.qualities = [{ id: -1, label: 'Auto' }];
                        this.isLoading = false;
                    }
                    video.addEventListener('waiting', () => this.isLoading = true);
                    video.addEventListener('playing', () => this.isLoading = false);
                    video.addEventListener('canplay', () => this.isLoading = false);
                },

                togglePlay() {
                    const video = this.$refs.video;
                    if (video.paused || video.ended) {
                        video.play();
                        this.isPlaying = true;
                    } else {
                        video.pause();
                        this.isPlaying = false;
                    }
                    this.triggerControls();
                },

                triggerControls() {
                    this.showControls = true;
                    this.resetTimer();
                },

                resetTimer() {
                    clearTimeout(this.timer);
                    if (this.isPlaying && !this.isSettingsOpen) {
                        this.timer = setTimeout(() => {
                            this.showControls = false;
                            this.isSettingsOpen = false;
                        }, 4000);
                    }
                },

                updateProgress() {
                    const video = this.$refs.video;
                    if(!video.duration) return;
                    this.currentTime = video.currentTime;
                    this.duration = video.duration;
                    this.progress = (this.currentTime / this.duration) * 100;
                    this.updateBuffer();
                },

                updateBuffer() {
                    const video = this.$refs.video;
                    if (!video.duration || video.buffered.length === 0) return;
                    for (let i = 0; i < video.buffered.length; i++) {
                        if (video.currentTime >= video.buffered.start(i) && video.currentTime <= video.buffered.end(i)) {
                            this.buffered = (video.buffered.end(i) / video.duration) * 100;
                            return;
                        }
                    }
                    if (video.buffered.length > 0) {
                         this.buffered = (video.buffered.end(video.buffered.length - 1) / video.duration) * 100;
                    }
                },

                seek(e) {
                    const value = e.target ? e.target.value : e;
                    const video = this.$refs.video;
                    const seekTime = (value / 100) * this.duration;
                    if (isFinite(seekTime)) {
                        video.currentTime = seekTime;
                        this.progress = value;
                    }
                    this.updateBuffer();
                },

                skip(seconds) {
                    const video = this.$refs.video;
                    video.currentTime += seconds;
                    this.triggerControls();
                    const direction = seconds > 0 ? 'right' : 'left';
                    this.showDoubleTapOverlay = direction;
                    setTimeout(() => this.showDoubleTapOverlay = null, 500);
                },

                handleZoneClick(side) {
                    const now = new Date().getTime();
                    const delta = now - this.lastTap;
                    if (delta < 300 && delta > 0) {
                        const seconds = side === 'left' ? -10 : 10;
                        this.skip(seconds);
                    } else {
                        this.togglePlay();
                    }
                    this.lastTap = now;
                },

                updateVolume() {
                    const video = this.$refs.video;
                    video.volume = this.volume;
                    this.muted = this.volume == 0;
                },

                toggleMute() {
                    const video = this.$refs.video;
                    if (this.muted) {
                        video.muted = false;
                        this.muted = false;
                        if (this.volume == 0) this.volume = 1;
                        video.volume = this.volume;
                    } else {
                        video.muted = true;
                        this.muted = true;
                    }
                },

                setSpeed(speed) {
                    this.$refs.video.playbackRate = speed;
                    this.playbackRate = speed;
                    this.settingsMenu = 'main';
                },

                setQuality(levelId) {
                    if (this.hls) {
                        this.hls.currentLevel = levelId;
                        this.currentQuality = levelId;
                    }
                    this.settingsMenu = 'main';
                },

                toggleFullscreen() {
                    const container = this.$refs.videoContainer;
                    if (!document.fullscreenElement) {
                        container.requestFullscreen().catch(err => console.log(err));
                        this.isFullscreen = true;
                    } else {
                        document.exitFullscreen();
                        this.isFullscreen = false;
                    }
                },

                formatTimeLeft() {
                    const left = this.duration - this.currentTime;
                    if (isNaN(left)) return "00:00";
                    const h = Math.floor(left / 3600);
                    const m = Math.floor((left % 3600) / 60);
                    const s = Math.floor(left % 60);
                    if (h > 0) return `${h}:${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
                    return `${m}:${s < 10 ? '0' : ''}${s}`;
                }
            }
        }
    </script>
</head>
<body class="min-h-screen flex items-center justify-center bg-[#141414] text-[#e5e5e5]">

    <div
        x-data="netflixPlayer()"
        x-init="initPlayer()"
        x-ref="videoContainer"
        class="relative w-full max-w-[1280px] aspect-video bg-black group shadow-2xl overflow-hidden select-none font-sans"
        @mousemove="triggerControls()"
        @mouseleave="showControls = false; isSettingsOpen = false"
        @keydown.window="if($event.key === ' ' || $event.key === 'k') { $event.preventDefault(); togglePlay(); }
                         if($event.key === 'f') toggleFullscreen();
                         if($event.key === 'ArrowRight') skip(10);
                         if($event.key === 'ArrowLeft') skip(-10);
                         if($event.key === 'm') toggleMute();"
        x-cloak
    >
        <!-- Loading Spinner -->
        <div x-show="isLoading" class="absolute inset-0 z-40 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="spinner"></div>
        </div>

        <!-- Video Element -->
        <video
            x-ref="video"
            class="w-full h-full object-contain cursor-pointer"
            :poster="poster"
            @timeupdate="updateProgress()"
            @progress="updateBuffer()"
            @loadedmetadata="duration = $el.duration"
            @ended="isPlaying = false; showControls = true"
            @click="togglePlay()"
            playsinline
        ></video>

        <!-- Double Tap Zones -->
        <div class="absolute inset-0 z-10 flex">
            <div @click="handleZoneClick('left')" class="w-1/3 h-full flex items-center justify-center relative cursor-pointer group/zone">
                <div x-show="showDoubleTapOverlay === 'left'" class="ripple-icon flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 fill-white drop-shadow-lg" viewBox="0 0 24 24"><path d="M11 18V6l-8.5 6 8.5 6zm.5-6l8.5 6V6l-8.5 6z"/></svg>
                    <span class="text-sm font-bold text-white drop-shadow-md mt-2">10s</span>
                </div>
            </div>
            <div @click="togglePlay()" class="w-1/3 h-full flex items-center justify-center">
                <div x-show="!isPlaying && !isLoading"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-125"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="bg-black/40 border-2 border-white/20 p-6 rounded-full backdrop-blur-sm hover:scale-110 hover:bg-white/10 transition-all cursor-pointer">
                    <svg class="w-10 h-10 fill-white ml-1" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </div>
            </div>
            <div @click="handleZoneClick('right')" class="w-1/3 h-full flex items-center justify-center relative cursor-pointer">
                <div x-show="showDoubleTapOverlay === 'right'" class="ripple-icon flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 fill-white drop-shadow-lg" viewBox="0 0 24 24"><path d="M4 18l8.5-6L4 6v12zm9-12v12l8.5-6L13 6z"/></svg>
                    <span class="text-sm font-bold text-white drop-shadow-md mt-2">10s</span>
                </div>
            </div>
        </div>

        <!-- Top Bar -->
        <div class="absolute top-0 left-0 right-0 h-24 bg-gradient-to-b from-black/80 to-transparent z-20 flex items-start justify-between p-6 transition-opacity duration-300"
            :class="showControls ? 'opacity-100' : 'opacity-0'">
            <button class="text-white hover:text-gray-300 transition-colors" onclick="history.back()">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="square" stroke-linejoin="square" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </button>
        </div>

        <!-- Bottom Controls -->
        <div class="absolute inset-x-0 bottom-0 z-30 transition-opacity duration-300"
            :class="(showControls || !isPlaying || isSettingsOpen) ? 'opacity-100' : 'opacity-0'">
            <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-black/90 via-black/60 to-transparent pointer-events-none -z-10"></div>

            <div class="px-4 md:px-8 pb-6">
                <!-- Progress Scrubber -->
                <div class="relative w-full group/progress h-4 flex items-center cursor-pointer mb-2">
                    <div class="absolute w-full h-[4px] bg-[#4d4d4d] rounded-sm"></div>
                    <div class="absolute h-[4px] bg-[#808080] rounded-sm transition-all duration-300" :style="`width: ${buffered}%`"></div>
                    <div class="absolute h-[4px] bg-netflix-red rounded-sm" :style="`width: ${progress}%`"></div>
                    <div class="absolute h-4 w-4 bg-netflix-red rounded-full scrubber-head shadow-lg ring-4 ring-white/10"
                         :style="`left: ${progress}%; margin-left: -8px`"></div>
                    <input type="range" min="0" max="100" step="0.1"
                        class="absolute w-full h-full opacity-0 cursor-pointer z-20"
                        :value="progress"
                        @input="seek($event); resetTimer()"
                        @mousedown="$el.classList.add('scrubber-dragging')"
                        @mouseup="$el.classList.remove('scrubber-dragging')"
                        @touchstart="$el.classList.add('scrubber-dragging')"
                        @touchend="$el.classList.remove('scrubber-dragging')">
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 md:gap-4">

                        <!-- Play/Pause -->
                        <button @click="togglePlay()" class="text-white hover:text-white/80 transition-colors focus:outline-none p-1">
                            <template x-if="!isPlaying">
                                <svg class="w-9 h-9 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </template>
                            <template x-if="isPlaying">
                                <svg class="w-9 h-9 fill-current" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                            </template>
                        </button>

                        <!-- HIGH VISIBILITY BACK 10s -->
                        <button @click="skip(-10)" class="relative group flex items-center justify-center w-12 h-12 rounded-full hover:bg-white/10 transition-colors focus:outline-none hidden sm:flex" title="Back 10s">
                            <!-- Circular Arrow SVG -->
                            <svg class="w-10 h-10 text-white fill-current opacity-90 group-hover:opacity-100 transition-opacity" viewBox="0 0 24 24">
                                <path d="M12.5 3C17.75 3 22 7.25 22 12.5c0 1.5-.35 2.92-.98 4.19l-1.84-1.07c.43-.93.67-1.97.67-3.12 0-4.05-3.2-7.35-7.35-7.35-2.22 0-4.21.99-5.58 2.54L10 10H3V3l2.84 2.84C7.86 4.09 10.07 3 12.5 3z"/>
                            </svg>
                            <!-- Text Centered -->
                            <span class="absolute text-[10px] font-black text-white pt-1 select-none font-sans">10</span>
                        </button>

                        <!-- HIGH VISIBILITY FORWARD 10s -->
                        <button @click="skip(10)" class="relative group flex items-center justify-center w-12 h-12 rounded-full hover:bg-white/10 transition-colors focus:outline-none hidden sm:flex" title="Forward 10s">
                             <!-- Flipped Arrow SVG -->
                            <svg class="w-10 h-10 text-white fill-current opacity-90 group-hover:opacity-100 transition-opacity" viewBox="0 0 24 24" style="transform: scaleX(-1);">
                                <path d="M12.5 3C17.75 3 22 7.25 22 12.5c0 1.5-.35 2.92-.98 4.19l-1.84-1.07c.43-.93.67-1.97.67-3.12 0-4.05-3.2-7.35-7.35-7.35-2.22 0-4.21.99-5.58 2.54L10 10H3V3l2.84 2.84C7.86 4.09 10.07 3 12.5 3z"/>
                            </svg>
                            <span class="absolute text-[10px] font-black text-white pt-1 select-none font-sans">10</span>
                        </button>

                        <!-- Volume -->
                        <div class="flex items-center group/volume ml-2">
                            <button @click="toggleMute()" class="text-white hover:text-white/80 transition-colors p-1">
                                <template x-if="muted || volume == 0">
                                    <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77zM3 9v6h4l5 5V4L7 9H3z"/></svg>
                                </template>
                                <template x-if="!muted && volume > 0">
                                    <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg>
                                </template>
                            </button>
                            <div class="w-0 sm:group-hover/volume:w-24 overflow-hidden transition-all duration-300 mx-0 sm:group-hover/volume:mx-2 flex items-center">
                                <input type="range" min="0" max="1" step="0.05" x-model="volume" @input="updateVolume()"
                                    class="w-20 h-1 bg-gray-500 rounded-lg cursor-pointer accent-netflix-red">
                            </div>
                        </div>
                    </div>

                    <div class="hidden md:flex flex-col items-center justify-center absolute left-1/2 -translate-x-1/2 bottom-6 pointer-events-none">
                         <h2 class="text-white font-bold text-lg drop-shadow-md" x-text="movieTitle"></h2>
                         <p class="text-gray-300 text-xs font-semibold" x-text="episodeTitle"></p>
                    </div>

                    <div class="flex items-center gap-4 md:gap-6">
                        <div class="text-sm font-bold text-gray-300" x-text="formatTimeLeft()"></div>
                        <div class="relative">
                            <button @click.stop="isSettingsOpen = !isSettingsOpen; settingsMenu = 'main'" class="text-white hover:text-white/80 transition-colors transform active:scale-95 p-1">
                                <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
                            </button>
                            <div x-show="isSettingsOpen" @click.away="isSettingsOpen = false" class="absolute bottom-14 right-[-10px] bg-black/95 border border-[#333] w-64 rounded-md shadow-2xl p-0 z-50 text-sm font-sans flex flex-col" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                                <div class="absolute -bottom-2 right-4 w-4 h-4 bg-black border-r border-b border-[#333] transform rotate-45"></div>
                                <div class="py-2" x-show="settingsMenu === 'main'">
                                    <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Settings</div>
                                    <button @click="settingsMenu = 'quality'" class="w-full px-4 py-3 flex items-center justify-between hover:bg-[#333] transition-colors">
                                        <span class="text-gray-200 font-medium">Video Quality</span>
                                        <span class="text-gray-400 text-xs" x-text="currentQuality === -1 ? 'Auto' : qualities.find(q=>q.id===currentQuality)?.label"></span>
                                    </button>
                                    <button @click="settingsMenu = 'speed'" class="w-full px-4 py-3 flex items-center justify-between hover:bg-[#333] transition-colors">
                                        <span class="text-gray-200 font-medium">Speed</span>
                                        <span class="text-gray-400 text-xs" x-text="playbackRate + 'x'"></span>
                                    </button>
                                </div>
                                <div class="py-2 max-h-60 overflow-y-auto custom-scrollbar" x-show="settingsMenu === 'quality'">
                                    <button @click="settingsMenu = 'main'" class="px-4 py-2 flex items-center text-gray-400 hover:text-white mb-2">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                        Back
                                    </button>
                                    <button @click="setQuality(-1)" class="w-full px-4 py-2 flex items-center gap-3 hover:bg-[#333]">
                                        <div class="w-4 h-4 flex items-center justify-center">
                                            <div x-show="currentQuality === -1" class="w-2 h-2 bg-netflix-red rounded-full"></div>
                                        </div>
                                        <span class="text-gray-200 font-bold">Auto</span>
                                    </button>
                                    <template x-for="q in qualities" :key="q.id">
                                        <button @click="setQuality(q.id)" class="w-full px-4 py-2 flex items-center gap-3 hover:bg-[#333]">
                                            <div class="w-4 h-4 flex items-center justify-center">
                                                <div x-show="currentQuality === q.id" class="w-2 h-2 bg-netflix-red rounded-full"></div>
                                            </div>
                                            <span class="text-gray-200 font-bold" x-text="q.label"></span>
                                        </button>
                                    </template>
                                </div>
                                <div class="py-2" x-show="settingsMenu === 'speed'">
                                    <button @click="settingsMenu = 'main'" class="px-4 py-2 flex items-center text-gray-400 hover:text-white mb-2">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                        Back
                                    </button>
                                    <div class="grid grid-cols-2 gap-1 px-2">
                                        <template x-for="speed in [0.5, 0.75, 1, 1.25, 1.5, 2]">
                                            <button @click="setSpeed(speed)" class="text-center py-2 rounded hover:bg-[#333] text-sm font-bold transition-colors"
                                            :class="playbackRate === speed ? 'text-netflix-red' : 'text-gray-300'" x-text="speed + 'x'"></button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button @click="toggleFullscreen()" class="text-white hover:text-white/80 transition-colors p-1">
                            <template x-if="!isFullscreen">
                                <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/></svg>
                            </template>
                            <template x-if="isFullscreen">
                                <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/></svg>
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
