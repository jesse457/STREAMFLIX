@extends('layouts.profile')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-[#141414] font-sans text-white">
    <!-- Main Container -->
    <div class="w-full max-w-[800px] px-8 animate-fade-in">

        <h1 class="text-4xl md:text-[4vw] font-medium mb-2 tracking-tight">Add Profile</h1>
        <p class="text-[#666] text-lg md:text-[1.3vw] mb-6">
            Add a profile for another person watching Netflix.
        </p>

        <!-- Divider -->
        <div class="w-full h-[1px] bg-[#333] mb-8"></div>

        <!-- Form -->
        <form action="{{ route('profiles.store') }}" method="POST">
            @csrf

            <div class="flex flex-col md:flex-row items-center gap-6 mb-8">

                <!-- Profile Avatar Preview -->
                <!-- Netflix avatars are perfectly square with very slight rounded corners (4px) -->
                <div class="w-24 h-24 md:w-[8vw] md:h-[8vw] shrink-0 rounded-[4px] overflow-hidden">
                    <img src="https://occ-0-1723-1722.1.nflxso.net/dnm/api/v6/K6hjPJd6cR6FpVELC5Pd6ovAW4k/AAAABfNXUMVXGhnCZwPI1SghnGpmUgqS_J-owMff-jigqn8onK9jlzu16dbqFRC73tnvtpJeNPIc-c8c4C_i3lPpH1g.png?r=fcd"
                         alt="Default Avatar"
                         class="w-full h-full object-cover">
                </div>

                <!-- Input Field Section -->
                <div class="flex-1 w-full">
                    <input
                        type="text"
                        name="name"
                        placeholder="Name"
                        autocomplete="off"
                        class="w-full bg-[#666] text-white placeholder-[#999] px-4 py-2 md:py-3 outline-none focus:bg-[#555] rounded-sm text-lg md:text-[1.3vw] transition-colors"
                        required
                    >
                    @error('name')
                        <span class="text-[#b00500] text-sm mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Kid Checkbox -->
                <div class="flex items-center gap-3 mt-4 md:mt-0 px-2 group cursor-pointer">
                    <div class="relative flex items-center">
                        <input type="checkbox" name="is_kid" id="is_kid"
                               class="peer appearance-none w-8 h-8 md:w-10 md:h-10 border-2 border-[#666] bg-transparent checked:bg-transparent checked:border-[#666] cursor-pointer rounded-none">
                        <!-- Custom Netflix Checkmark -->
                        <svg class="absolute w-6 h-6 md:w-8 md:h-8 hidden peer-checked:block pointer-events-none text-white left-1"
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="square" stroke-linejoin="square">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <label for="is_kid" class="text-xl md:text-[1.3vw] font-light select-none cursor-pointer tracking-wide">Kid?</label>
                </div>
            </div>

            <!-- Divider -->
            <div class="w-full h-[1px] bg-[#333] mb-10"></div>

            <!-- Buttons Row -->
            <div class="flex flex-wrap gap-5">
                <!-- Continue Button: Netflix style is white with bold text, hover to red -->
                <button type="submit"
                        class="bg-white text-black text-lg md:text-[1.2vw] font-bold px-8 py-2 md:px-10 md:py-2.5 hover:bg-[#e50914] hover:text-white transition-all duration-200 uppercase tracking-[2px]">
                    Continue
                </button>

                <!-- Cancel Button: Gray border, gray text, hover to white -->
                <a href="{{ route('profiles.index') }}"
                   class="border border-[#666] text-[#666] text-lg md:text-[1.2vw] font-semibold px-8 py-2 md:px-10 md:py-2.5 hover:border-white hover:text-white transition-all duration-200 uppercase tracking-[2px] flex items-center justify-center">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

<style>
    /* Clean Netflix Fade-In */
    @keyframes profileFade {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: profileFade 0.45s ease-out forwards;
    }

    /* Input placeholder styling */
    input::placeholder {
        font-weight: 300;
    }
</style>
@endsection
