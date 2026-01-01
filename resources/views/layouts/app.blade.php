<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Netflix Clone') }}</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        netflix: {
                            black: '#141414',
                            dark: '#181818',
                            red: '#E50914',
                            hover: '#b20710',
                            gray: '#808080'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { background-color: #141414; color: white; }
        [x-cloak] { display: none !important; }
        /* Smooth Scroll */
        html { scroll-behavior: smooth; }
        /* Hide scrollbar for horizontal lists but keep functionality */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="font-sans antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav x-data="{ scrolled: false }"
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="scrolled ? 'bg-netflix-black shadow-lg' : 'bg-gradient-to-b from-black/80 to-transparent'"
         class="fixed w-full z-50 transition-colors duration-300 top-0">

        <div class="px-4 md:px-12">
            <div class="flex items-center justify-between h-16">
                <!-- Left Side -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('browse.index') }}" class="text-2xl md:text-3xl font-black text-netflix-red tracking-tighter uppercase">STREAMFLIX</a>

                    <div class="hidden md:flex items-baseline space-x-6 text-sm font-light text-gray-300">
                        <a href="{{ route('browse.index') }}" class="text-white font-bold cursor-default">Home</a>
                        <a href="#" class="hover:text-white transition">Series</a>
                        <a href="#" class="hover:text-white transition">Movies</a>
                        <a href="#" class="hover:text-white transition">My List</a>
                    </div>
                </div>

                <!-- Right Side -->
                <div class="flex items-center space-x-6">
                    <!-- Search -->
                    <button class="text-white hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>

                    <!-- Profile Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 focus:outline-none">
                            <!-- Dynamic Avatar from Session/DB -->
                            <img src="{{ Auth::user()->profiles()->find(session('current_profile_id'))->avatar ?? 'https://upload.wikimedia.org/wikipedia/commons/0/0b/Netflix-avatar.png' }}"
                                 class="w-8 h-8 rounded hover:ring-2 hover:ring-white transition"
                                 alt="Avatar">
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-cloak x-transition
                             class="absolute right-0 mt-2 w-48 bg-black/95 border border-gray-700 rounded shadow-xl py-2">

                            <a href="{{ route('profiles.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:text-white hover:underline">
                                Manage Profiles
                            </a>
                            <hr class="border-gray-700 my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:text-white hover:underline">
                                    Sign out of Netflix
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#141414] text-netflix-gray py-12 px-4 md:px-12 text-sm mt-auto border-t border-gray-800/40">
        <div class="max-w-5xl mx-auto text-center md:text-left">
            <p>&copy; {{ date('Y') }} StreamFlix Clone. Built for Resume.</p>
        </div>
    </footer>
</body>
</html>
