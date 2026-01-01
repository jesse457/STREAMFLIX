<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix - Add Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #141414; }
        .netflix-input {
            background: #666;
            color: white;
        }
        .netflix-input::placeholder { color: #ccc; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center font-sans text-white">

    <!-- Container -->
    <div class="w-full max-w-2xl px-4 animate-fade-in">

        <h1 class="text-4xl md:text-6xl font-medium mb-4">Add Profile</h1>
        <p class="text-[#666] text-lg md:text-xl mb-8">
            Add a profile for another person watching Netflix.
        </p>

        <hr class="border-[#333] mb-8">

        <!-- Form -->
        <form action="{{ route('profiles.store') }}" method="POST">
            @csrf

            <div class="flex flex-col md:flex-row items-center gap-6 mb-8">

                <!-- Static Preview Avatar -->
                <div class="w-24 h-24 md:w-32 md:h-32 rounded bg-blue-500 overflow-hidden shadow-lg">
                    <img src="https://occ-0-1723-1722.1.nflxso.net/dnm/api/v6/K6hjPJd6cR6FpVELC5Pd6ovAW4k/AAAABfNXUMVXGhnCZwPI1SghnGpmUgqS_J-owMff-jigqn8onK9jlzu16dbqFRC73tnvtpJeNPIc-c8c4C_i3lPpH1g.png?r=fcd" class="w-full h-full object-cover">
                </div>

                <!-- Input Section -->
                <div class="flex-1 w-full">
                    <input
                        type="text"
                        name="name"
                        placeholder="Name"
                        class="w-full bg-[#666] text-white placeholder-gray-300 px-4 py-3 md:py-4 outline-none focus:bg-[#555] rounded text-lg"
                        required
                    >
                    @error('name')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Kid Checkbox (Netflix Style) -->
                <div class="flex items-center gap-2 mt-2 md:mt-0">
                    <input type="checkbox" name="is_kid" id="is_kid" class="w-6 h-6 bg-black border-gray-500 rounded focus:ring-0 text-red-600">
                    <label for="is_kid" class="text-lg tracking-wide select-none cursor-pointer">Kid?</label>
                </div>
            </div>

            <hr class="border-[#333] mb-8">

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="bg-white text-black text-lg font-bold px-8 py-2 hover:bg-[#c00] hover:text-white transition-colors uppercase tracking-widest border border-white hover:border-[#c00]">
                    Continue
                </button>

                <a href="{{ route('profiles.index') }}" class="border border-[#666] text-[#666] text-lg font-bold px-8 py-2 hover:border-white hover:text-white transition-colors uppercase tracking-widest">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</body>
</html>
