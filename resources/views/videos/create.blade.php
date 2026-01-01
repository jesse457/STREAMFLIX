@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 py-24 bg-cover bg-center" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.9)), url('https://assets.nflxext.com/ffe/siteui/vlv3/f841d4c7-10e1-40af-bcae-07a3f8dc141a/f6d7434e-d6de-4185-a6d4-c77a2d08737b/US-en-20220502-popsignuptwoweeks-perspective_alpha_website_medium.jpg');">

        <div class="w-full max-w-lg bg-black/80 backdrop-blur-sm p-8 md:p-12 rounded-lg border border-transparent shadow-2xl" x-data="videoUpload()">

            <h2 class="text-3xl font-bold text-white mb-8">Add to Library</h2>

            <form @submit.prevent="submitForm">
                @csrf

                <!-- Title -->
                <div class="mb-6 relative">
                    <input type="text" x-model="formData.title" id="title"
                        class="block w-full px-4 py-4 rounded bg-[#333] text-white border-none focus:ring-0 focus:bg-[#454545] peer placeholder-transparent transition"
                        placeholder="Video Title">
                    <label for="title"
                        class="absolute text-gray-400 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3">
                        Video Title
                    </label>
                </div>

                <!-- File Drop Zone -->
                <div class="mb-8">
                    <div class="relative w-full h-40 border-2 border-dashed border-gray-600 rounded-lg bg-[#181818] hover:bg-[#202020] hover:border-white transition flex flex-col items-center justify-center cursor-pointer group"
                        :class="{ 'border-netflix-red bg-[#222]': isDragging }"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="isDragging = false"
                        @drop.prevent="handleDrop($event)"
                        @click="$refs.fileInput.click()">

                        <input type="file" x-ref="fileInput" @change="handleFileSelect" class="hidden" accept="video/*">

                        <div x-show="!fileName" class="text-center p-4">
                            <svg class="w-10 h-10 mx-auto text-gray-500 group-hover:text-white transition mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="text-sm text-gray-400">Drag video here or click to browse</p>
                        </div>

                        <div x-show="fileName" class="text-center p-4 w-full">
                            <div class="flex items-center justify-center space-x-2 text-green-500 mb-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-bold text-sm truncate max-w-[200px]" x-text="fileName"></span>
                            </div>
                            <button type="button" @click.stop="resetFile" class="text-xs text-netflix-red hover:underline uppercase font-bold tracking-wide">Change File</button>
                        </div>
                    </div>
                </div>

                <!-- Progress -->
                <div x-show="uploading" class="mb-6">
                    <div class="w-full bg-gray-700 h-1 rounded overflow-hidden">
                        <div class="bg-netflix-red h-full transition-all duration-200" :style="'width: ' + progress + '%'"></div>
                    </div>
                    <p class="text-right text-xs text-gray-400 mt-2">Uploading... <span x-text="progress + '%'"></span></p>
                </div>

                <!-- Messages -->
                <div x-show="error" class="mb-6 bg-[#e87c03] text-white p-3 rounded text-sm rounded flex items-start" x-cloak>
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <span x-text="error"></span>
                        <ul class="list-disc list-inside mt-1 ml-1" x-show="errorDetails.length > 0">
                            <template x-for="detail in errorDetails">
                                <li x-text="detail"></li>
                            </template>
                        </ul>
                    </div>
                </div>

                <button type="submit"
                    :disabled="uploading || !fileName"
                    class="w-full bg-netflix-red hover:bg-netflix-hover text-white font-bold py-4 rounded disabled:opacity-50 disabled:cursor-not-allowed transition duration-200 text-lg">
                    <span x-show="!uploading">Upload Video</span>
                    <span x-show="uploading">Processing...</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Reusing the script from your original code, it works well -->
    <script>
        function videoUpload() {
            return {
                formData: { title: '' },
                file: null, fileName: null, isDragging: false, uploading: false, progress: 0, message: '', error: '', errorDetails: [],
                handleFileSelect(e) { const file = e.target.files[0]; if (file) this.setFile(file); },
                handleDrop(e) { this.isDragging = false; const file = e.dataTransfer.files[0]; if (file) this.setFile(file); },
                setFile(file) {
                    if (file.type.indexOf('video/') === -1) { this.error = 'Please upload a valid video file.'; return; }
                    this.file = file; this.fileName = file.name; this.error = '';
                },
                resetFile() { this.file = null; this.fileName = null; this.$refs.fileInput.value = null; },
                submitForm() {
                    if (!this.file || !this.formData.title) { this.error = "Title and Video are required."; return; }
                    this.uploading = true; this.error = ''; this.errorDetails = [];
                    let form = new FormData();
                    form.append('title', this.formData.title);
                    form.append('video', this.file);
                    const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.content;
                    axios.post("{{ route('videos.store') }}", form, {
                        headers: { 'Content-Type': 'multipart/form-data', 'X-CSRF-TOKEN': csrfToken },
                        onUploadProgress: (e) => { this.progress = Math.round((e.loaded * 100) / e.total); }
                    }).then(res => {
                        window.location.href = "{{ route('videos.index') }}";
                    }).catch(err => {
                        this.uploading = false; this.progress = 0;
                        if (err.response && err.response.status === 422) {
                            this.error = "Validation Failed";
                            this.errorDetails = Object.values(err.response.data.errors).flat();
                        } else {
                            this.error = "Upload failed. Check console/logs.";
                        }
                    });
                }
            }
        }
    </script>
@endsection
