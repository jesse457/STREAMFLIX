<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Netflix Clone') }}</title>

   @vite(['resources/css/app.css', 'resources/js/app.js'])


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
