<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix - Who's Watching?</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #141414;
        }

        /* Smooth zoom effect on hover */
        .profile-card:hover .avatar-img {
            border-color: white;
        }

        .profile-card:hover .profile-name {
            color: white;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen font-sans text-white">

    <!-- Main Container -->
    <div class="flex flex-col items-center animate-fade-in">

        <!-- Header -->
        <h1 class="text-3xl md:text-5xl font-medium mb-8 md:mb-12 drop-shadow-lg text-center">
            Who's watching?
        </h1>

        <!-- Profile Grid -->
        <!-- Flex on mobile, Row on desktop -->
        <div class="flex flex-wrap justify-center gap-4 md:gap-8 mb-12 md:mb-16">

            @foreach ($profiles as $profile)
                <!-- Individual Profile Item -->
                <!-- We wrap it in a form to handle the POST request for session switching -->
                <form action="{{ route('profiles.switch', $profile->id) }}" method="POST"
                    class="group profile-card w-24 md:w-40 cursor-pointer text-center">
                    @csrf
                    <button type="submit" class="w-full focus:outline-none">
                        <!-- Avatar Image -->
                        <div
                            class="avatar-img w-24 h-24 md:w-40 md:h-40 rounded-md overflow-hidden border-4 border-transparent box-border transition-all duration-200 group-hover:border-white">
                            <!-- Default avatar if none exists -->
                            <img src="{{ $profile->avatar ?? 'https://upload.wikimedia.org/wikipedia/commons/0/0b/Netflix-avatar.png' }}"
                                alt="{{ $profile->name }}" class="w-full h-full object-cover">
                        </div>

                        <!-- Profile Name -->
                        <span
                            class="profile-name block mt-4 text-gray-500 text-xs md:text-xl transition-colors duration-200 group-hover:text-white">
                            {{ $profile->name }}
                        </span>
                    </button>
                </form>
            @endforeach

            <!-- Add Profile Button (Visual Only for Resume) -->
            <!-- Add Profile Button (Functional) -->
            <a href="{{ route('profiles.create') }}"
                class="group profile-card w-24 md:w-40 cursor-pointer text-center inline-block">
                <div
                    class="avatar-img w-24 h-24 md:w-40 md:h-40 rounded-md flex items-center justify-center bg-transparent border-4 border-transparent group-hover:bg-white group-hover:border-white transition-all duration-200">
                    <!-- Plus Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-500 group-hover:text-gray-800"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" />
                    </svg>
                </div>
                <span
                    class="profile-name block mt-4 text-gray-500 text-xs md:text-xl transition-colors duration-200 group-hover:text-white">
                    Add Profile
                </span>
            </a>

        </div>

        <!-- Manage Profiles Button -->
        <button
            class="border border-gray-500 text-gray-500 px-6 py-2 md:px-8 md:py-2.5 text-sm md:text-lg uppercase tracking-widest hover:border-white hover:text-white transition-colors duration-200">
            Manage Profiles
        </button>

    </div>

</body>

</html>
