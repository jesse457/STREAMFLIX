@extends('layouts.profile')

@section('content')
    <!-- Netflix Background Color #141414 -->
    <div class="flex items-center justify-center min-h-screen bg-[#141414] font-sans text-white overflow-hidden">

        <!-- Main Animation Container -->
        <div class="flex flex-col items-center animate-fade-in w-full max-w-5xl px-4">

            <!-- Header -->
            <h1 class="text-3xl md:text-[3.5rem] font-medium mb-8 md:mb-12 text-center text-white drop-shadow-md select-none">
                Who's watching?
            </h1>

            <!-- Profile Grid -->
            <div class="flex flex-wrap justify-center gap-4 md:gap-8 mb-16 md:mb-24">

                @foreach ($profiles as $profile)
                    <!-- Individual Profile Item -->
                    <form action="{{ route('profiles.switch', $profile->id) }}" method="POST"
                        class="group w-24 md:w-[10rem] flex flex-col items-center cursor-pointer">
                        @csrf
                        <button type="submit" class="w-full focus:outline-none">

                            <!-- Avatar Image Wrapper -->
                            <!-- Netflix borders are transparent by default, white on hover, approx 2-3px -->
                            <div class="w-24 h-24 md:w-[10rem] md:h-[10rem] rounded-md overflow-hidden border-2 border-transparent group-hover:border-white transition-colors duration-200 box-border">
                                <img src="{{ $profile->avatar ?? 'https://upload.wikimedia.org/wikipedia/commons/0/0b/Netflix-avatar.png' }}"
                                    alt="{{ $profile->name }}"
                                    class="w-full h-full object-cover">
                            </div>

                            <!-- Profile Name -->
                            <!-- Text is #808080 (gray) and turns white on hover -->
                            <span class="block mt-4 text-[#808080] text-xs md:text-xl text-center group-hover:text-white transition-colors duration-200 select-none truncate px-1">
                                {{ $profile->name }}
                            </span>
                        </button>
                    </form>
                @endforeach

                <!-- Add Profile Button -->
                <a href="{{ route('profiles.create') }}"
                    class="group w-24 md:w-[10rem] flex flex-col items-center cursor-pointer no-underline">

                    <!-- Icon Wrapper -->
                    <div class="w-24 h-24 md:w-[10rem] md:h-[10rem] flex items-center justify-center rounded-md border-2 border-transparent group-hover:bg-white group-hover:border-white transition-colors duration-200 box-border relative">
                        <!-- Default State: Dark Circle with Plus -->
                        <div class="bg-transparent group-hover:hidden w-full h-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 md:h-20 md:w-20 text-[#808080]" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                            </svg>
                        </div>

                        <!-- Hover State: Plus Icon becomes dark inside white bg -->
                        <!-- Note: Netflix usually creates this effect by swapping backgrounds, simplified here -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="hidden group-hover:block h-12 w-12 md:h-20 md:w-20 text-[#808080]" viewBox="0 0 20 20" fill="currentColor">
                             <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                        </svg>
                    </div>

                    <!-- Add Profile Text -->
                    <span class="block mt-4 text-[#808080] text-xs md:text-xl text-center group-hover:text-white transition-colors duration-200 select-none">
                        Add Profile
                    </span>
                </a>

            </div>

            <!-- Manage Profiles Button -->
            <!-- Clean, uppercase, wide tracking, border gray-500 -->
            <button
                class="border border-[#808080] text-[#808080] px-6 py-2 md:px-10 md:py-2 text-[13px] md:text-[1.2vw] tracking-[2px] uppercase hover:border-white hover:text-white transition-all duration-200 select-none bg-transparent">
                Manage Profiles
            </button>

        </div>
    </div>

    <style>
        /* Custom Fade In Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(1.1); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        /* Force font smoothing for that crisp Netflix look */
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
@endsection
