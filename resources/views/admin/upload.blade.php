<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix Studio - Upload</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { background-color: #141414; color: white; }
    </style>
</head>
<body class="font-sans antialiased min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-black border-r border-gray-800 hidden md:flex flex-col">
        <div class="h-16 flex items-center px-8 border-b border-gray-800">
            <span class="text-red-600 font-black text-2xl tracking-tighter">STUDIO</span>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="#" class="flex items-center gap-3 px-4 py-3 bg-[#333] text-white rounded font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Upload Video
            </a>
            <a href="{{ route('browse.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Back to Netflix
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col relative"
          x-data="fileUpload()">

        <!-- Header -->
        <header class="h-16 border-b border-gray-800 flex items-center justify-between px-8 bg-black/50 backdrop-blur">
            <h1 class="text-xl font-bold">Upload Content</h1>
            <div class="text-sm text-gray-400">Admin Mode</div>
        </header>

        <!-- Upload Form Area -->
        <div class="flex-1 p-8 overflow-y-auto">
            <div class="max-w-3xl mx-auto">

                <!-- Success Message -->
                <div x-show="success" x-transition class="mb-6 bg-green-500/20 border border-green-500 text-green-400 px-4 py-3 rounded flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <div>
                        <p class="font-bold">Upload Complete!</p>
                        <p class="text-sm">Your video is being processed in the background.</p>
                    </div>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-6">

                    <!-- Title Input -->
                    <div>
                        <label class="block text-gray-400 text-sm font-bold mb-2">Title</label>
                        <input x-model="formData.title" type="text" class="w-full bg-[#333] border-none text-white rounded px-4 py-3 focus:ring-2 focus:ring-red-600 outline-none placeholder-gray-500" placeholder="e.g. Stranger Things: Season 4">
                    </div>

                    <!-- Description Input -->
                    <div>
                        <label class="block text-gray-400 text-sm font-bold mb-2">Description</label>
                        <textarea x-model="formData.description" rows="3" class="w-full bg-[#333] border-none text-white rounded px-4 py-3 focus:ring-2 focus:ring-red-600 outline-none placeholder-gray-500" placeholder="Movie synopsis..."></textarea>
                    </div>

                    <!-- Drag & Drop Zone -->
                    <div>
                        <label class="block text-gray-400 text-sm font-bold mb-2">Video File</label>
                        <div
                            class="border-2 border-dashed border-gray-600 rounded-lg p-10 text-center transition-colors duration-200"
                            :class="{'border-red-600 bg-red-600/10': isDragging, 'bg-[#333]': !isDragging}"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleDrop($event)"
                        >
                            <input type="file" x-ref="fileInput" @change="handleFileSelect" class="hidden" accept="video/*">

                            <div x-show="!file">
                                <div class="mb-4 text-gray-400">
                                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                </div>
                                <p class="text-lg mb-2">Drag and drop video files to upload</p>
                                <p class="text-sm text-gray-500">Your videos will be private until you publish them.</p>
                                <button type="button" @click="$refs.fileInput.click()" class="mt-4 bg-white text-black px-6 py-2 rounded font-bold hover:bg-gray-200 transition">
                                    Select Files
                                </button>
                            </div>

                            <!-- File Selected View -->
                            <div x-show="file" class="flex items-center justify-between bg-[#222] p-4 rounded">
                                <div class="flex items-center gap-3">
                                    <div class="bg-red-600 p-2 rounded">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-bold text-sm" x-text="file?.name"></p>
                                        <p class="text-xs text-gray-500" x-text="formatSize(file?.size)"></p>
                                    </div>
                                </div>
                                <button type="button" @click="file = null" class="text-gray-400 hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div x-show="uploading" class="w-full bg-gray-700 rounded-full h-2.5 mb-4">
                        <div class="bg-red-600 h-2.5 rounded-full transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                    </div>
                    <p x-show="uploading" class="text-center text-xs text-gray-400" x-text="'Uploading... ' + progress + '%'"></p>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4">
                        <button type="submit"
                                :disabled="uploading || !file"
                                class="bg-red-600 text-white px-8 py-3 rounded font-bold hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            Upload Video
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <!-- Alpine.js Logic -->
    <script>
        function fileUpload() {
            return {
                isDragging: false,
                file: null,
                formData: {
                    title: '',
                    description: ''
                },
                uploading: false,
                progress: 0,
                success: false,

                handleDrop(e) {
                    this.isDragging = false;
                    const files = e.dataTransfer.files;
                    if (files.length > 0) this.validateAndSetFile(files[0]);
                },

                handleFileSelect(e) {
                    const files = e.target.files;
                    if (files.length > 0) this.validateAndSetFile(files[0]);
                },

                validateAndSetFile(file) {
                    if (file.type.startsWith('video/')) {
                        this.file = file;
                    } else {
                        alert('Please upload a valid video file.');
                    }
                },

                formatSize(bytes) {
                    if (!bytes) return '0 B';
                    const k = 1024;
                    const sizes = ['B', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                },

                submit() {
                    if (!this.file || !this.formData.title) {
                        alert('Please provide a title and a video file.');
                        return;
                    }

                    this.uploading = true;
                    this.progress = 0;
                    this.success = false;

                    const data = new FormData();
                    data.append('video', this.file);
                    data.append('title', this.formData.title);
                    data.append('description', this.formData.description);
                    // Add CSRF Token for Laravel
                    data.append('_token', '{{ csrf_token() }}');

                    axios.post('{{ route('admin.store') }}', data, {
                        onUploadProgress: (progressEvent) => {
                            const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                            this.progress = percentCompleted;
                        }
                    })
                    .then(response => {
                        this.uploading = false;
                        this.success = true;
                        this.file = null;
                        this.formData.title = '';
                        this.formData.description = '';
                        // Optionally refresh page or add to a list
                    })
                    .catch(error => {
                        this.uploading = false;
                        console.error(error);
                        alert(error.response.data.message || 'Upload failed');
                    });
                }
            }
        }
    </script>
</body>
</html>
