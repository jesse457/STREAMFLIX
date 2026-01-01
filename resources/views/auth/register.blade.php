<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix - Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom Netflix Red */
        .bg-netflix-red { background-color: #e50914; }
        .bg-netflix-red:hover { background-color: #c11119; }

        /* Background Image */
        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://assets.nflxext.com/ffe/siteui/vlv3/f841d4c7-10e1-40af-bcae-07a3f8dc141a/f6d7434e-d6de-4185-a6d4-c77a2d08737b/US-en-20220502-popsignuptwoweeks-perspective_alpha_website_medium.jpg');
            background-size: cover;
            background-position: center;
        }

        /* Floating Label Logic */
        .form-input:focus ~ .form-label,
        .form-input:not(:placeholder-shown) ~ .form-label {
            top: 0.5rem;
            font-size: 0.75rem;
            transform: translateY(0);
        }
    </style>
</head>
<body class="hero-bg min-h-screen flex flex-col font-sans text-white">

    <!-- Navbar (Logo + Sign In Button) -->
    <div class="px-4 md:px-8 py-6 w-full flex justify-between items-center z-10">
        <img src="https://upload.wikimedia.org/wikipedia/commons/0/08/Netflix_2015_logo.svg" alt="Netflix" class="h-8 md:h-12">
        <a href="{{ route('login') }}" class="text-white font-medium hover:underline">
            Sign In
        </a>
    </div>

    <!-- Register Container -->
    <div class="flex-grow flex items-center justify-center px-4 z-10">
        <div class="bg-black/80 p-8 md:p-16 rounded-lg w-full max-w-[450px] min-h-[550px] shadow-2xl backdrop-blur-sm">

            <h1 class="text-3xl font-bold mb-2">Sign Up</h1>
            <p class="text-gray-400 mb-8 text-sm">Join to start watching.</p>

            <!-- Error Message Box -->
            @if ($errors->any())
                <div class="bg-[#e87c03] p-4 rounded mb-6 text-sm">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span class="font-bold">Please fix the errors below</span>
                    </div>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div class="relative bg-[#333] rounded overflow-hidden group focus-within:ring-1 focus-within:ring-white">
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-input block w-full px-5 pt-6 pb-2 bg-[#333] text-white border-0 focus:ring-0 focus:bg-[#454545] placeholder-transparent transition-colors peer"
                        placeholder="Email"
                        value="{{ old('email') }}"
                        required
                    >
                    <label
                        for="email"
                        class="form-label absolute text-[#8c8c8c] top-4 left-5 text-base transition-all duration-200 pointer-events-none peer-focus:text-xs peer-focus:top-2"
                    >
                        Email address
                    </label>
                </div>

                <!-- Password Input -->
                <div class="relative bg-[#333] rounded overflow-hidden group focus-within:ring-1 focus-within:ring-white">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-input block w-full px-5 pt-6 pb-2 bg-[#333] text-white border-0 focus:ring-0 focus:bg-[#454545] placeholder-transparent transition-colors peer"
                        placeholder="Password"
                        required
                    >
                    <label
                        for="password"
                        class="form-label absolute text-[#8c8c8c] top-4 left-5 text-base transition-all duration-200 pointer-events-none peer-focus:text-xs peer-focus:top-2"
                    >
                        Password
                    </label>
                </div>

                <!-- Confirm Password Input -->
                <div class="relative bg-[#333] rounded overflow-hidden group focus-within:ring-1 focus-within:ring-white">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="form-input block w-full px-5 pt-6 pb-2 bg-[#333] text-white border-0 focus:ring-0 focus:bg-[#454545] placeholder-transparent transition-colors peer"
                        placeholder="Confirm Password"
                        required
                    >
                    <label
                        for="password_confirmation"
                        class="form-label absolute text-[#8c8c8c] top-4 left-5 text-base transition-all duration-200 pointer-events-none peer-focus:text-xs peer-focus:top-2"
                    >
                        Confirm Password
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-netflix-red text-white font-bold py-3.5 rounded mt-6 mb-2 hover:shadow-lg transition duration-200">
                    Create Account
                </button>

            </form>

            <!-- Bottom Section -->
            <div class="mt-8 text-[#737373]">
                <p class="text-base mb-4">
                    Already have an account? <a href="{{ route('login') }}" class="text-white hover:underline font-medium">Sign in now</a>.
                </p>
                <p class="text-xs leading-tight">
                    This page is protected by Google reCAPTCHA to ensure you're not a bot.
                    <a href="#" class="text-blue-500 hover:underline">Learn more</a>.
                </p>
            </div>
        </div>
    </div>

    <!-- Simple Footer -->
    <div class="bg-black/80 py-8 border-t border-gray-800 z-10">
        <div class="max-w-4xl mx-auto px-4 text-[#737373] text-sm">
            <p class="mb-2">Questions? Call 1-844-505-2993</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">
                <a href="#" class="hover:underline">FAQ</a>
                <a href="#" class="hover:underline">Help Center</a>
                <a href="#" class="hover:underline">Terms of Use</a>
                <a href="#" class="hover:underline">Privacy</a>
            </div>
        </div>
    </div>

</body>
</html>
