<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix - Login</title>
    <!-- Tailwind CSS CDN for instant styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom Netflix Red */
        .bg-netflix-red { background-color: #e50914; }
        .bg-netflix-red:hover { background-color: #c11119; }

        /* Background Image (Netflix-style collage) */
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

    <!-- Navbar (Logo only) -->
    <div class="px-8 py-6 w-full flex justify-between items-center">
        <img src="https://upload.wikimedia.org/wikipedia/commons/0/08/Netflix_2015_logo.svg" alt="Netflix" class="h-8 md:h-12">
    </div>

    <!-- Login Container -->
    <div class="flex-grow flex items-center justify-center px-4">
        <div class="bg-black/75 p-16 rounded-lg w-full max-w-[450px] min-h-[600px]">
            <h1 class="text-3xl font-bold mb-8">Sign In</h1>

            <!-- Error Message Box -->
            @if ($errors->any())
                <div class="bg-[#e87c03] p-4 rounded mb-4 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/login" method="POST" class="space-y-4">
                @csrf

                <!-- Email Input with Floating Label -->
                <div class="relative bg-[#333] rounded overflow-hidden">
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
                        Email or phone number
                    </label>
                </div>

                <!-- Password Input with Floating Label -->
                <div class="relative bg-[#333] rounded overflow-hidden">
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

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-netflix-red text-white font-bold py-3.5 rounded mt-6 mb-2">
                    Sign In
                </button>

                <!-- Helper Links -->
                <div class="flex justify-between items-center text-[#b3b3b3] text-sm mt-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 bg-[#333] border-0 rounded focus:ring-0 checked:bg-[#737373]">
                        <label for="remember" class="ml-2 hover:underline cursor-pointer">Remember me</label>
                    </div>
                    <a href="#" class="hover:underline">Need help?</a>
                </div>
            </form>

            <!-- Bottom Section -->
            <div class="mt-16 text-[#737373]">
                <p class="text-base mb-4">
                    New to Netflix? <a href="/register" class="text-white hover:underline">Sign up now</a>.
                </p>
                <p class="text-xs">
                    This page is protected by Google reCAPTCHA to ensure you're not a bot.
                    <a href="#" class="text-blue-500 hover:underline">Learn more</a>.
                </p>
            </div>
        </div>
    </div>

    <!-- Footer Dummy -->
    <div class="bg-black/75 py-8 mt-auto border-t border-gray-800">
        <div class="max-w-4xl mx-auto px-4 text-[#737373] text-sm">
            <p>Questions? Call 1-844-505-2993</p>
        </div>
    </div>

</body>
</html>
