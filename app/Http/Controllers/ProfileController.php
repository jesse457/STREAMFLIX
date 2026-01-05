<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // 1. The "Who's Watching" Screen
    public function index()
    {
        $user = Auth::user();
        $profiles = $user->profiles;

        // NETFLIX LOGIC: If a new user has 0 profiles, force them to create one immediately.
        if ($profiles->isEmpty()) {
            return redirect()->route('profiles.create');
        }

        return view('profiles.index', [
            'profiles' => $profiles,
        ]);
    }

    // 2. Show the "Add Profile" Form
    public function create()
    {
        // Limit: Netflix usually allows max 5 profiles
        if (Auth::user()->profiles()->count() >= 5) {
            return redirect()->route('profiles.index')->withErrors(['msg' => 'Maximum profile limit reached.']);
        }

        return view('profiles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20',
        ]);

        Auth::user()->profiles()->create([
            'name' => $request->name,
            'avatar' => $this->getRandomAvatar(),
            'is_kid' => $request->has('is_kid'),
        ]);

        return redirect()->route('profiles.index');
    }

    private function getRandomAvatar()
    {
        // These are Base64 encoded versions of the Netflix-style smiley SVGs
        $avatars = [
            // Blue Avatar
            'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzJiNTljZSIvPjxwYXRoIGQ9Ik0yNSAzNWMwLTIgMi00IDQtNGg2YzIgMCA0IDIgNCA0djEwYzAgMi0yIDQtNCA0aC02Yy0yIDAtNC0yLTQtNFYzNXptMzYgMGMwLTIgMi00IDQtNGg2YzIgMCA0IDIgNCA0djEwYzAgMi0yIDQtNCA0aC02Yy0yIDAtNC0yLTQtNFYzNXptLTMzIDMwaDQ0YzIgMCA0IDIgNCA0djRjMCAyLTIsNCA0IDRKMjhjLTIsMC00LTItNC00di00YzAtMiAyLTQgNC00eiIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg==',

            // Red Avatar
            'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2U1MDkxNCIvPjxwYXRoIGQ9Ik0yNSAzNWMwLTIgMi00IDQtNGg2YzIgMCA0IDIgNCA0djEwYzAgMi0yIDQtNCA0aC02Yy0yIDAtNC0yLTQtNFYzNXptMzYgMGMwLTIgMi00IDQtNGg2YzIgMCA0IDIgNCA0djEwYzAgMi0yIDQtNCA0aC02Yy0yIDAtNC0yLTQtNFYzNXptLTMzIDMwaDQ0YzIgMCA0IDIgNCA0djRjMCAyLTIsNCA0IDRKMjhjLTIsMC00LTItNC00di00YzAtMiAyLTQgNC00eiIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg==',

            // Yellow Avatar
            'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2Y1YzUxOCIvPjxwYXRoIGQ9Ik0yNSAzNWMwLTIgMi00IDQtNGg2YzIgMCA0IDIgNCA0djEwYzAgMi0yIDQtNCA0aC02Yy0yIDAtNC0yLTQtNFYzNXptMzYgMGMwLTIgMi00IDQtNGg2YzIgMCA0IDIgNCA0djEwYzAgMi0yIDQtNCA0aC02Yy0yIDAtNC0yLTQtNFYzNXptLTMzIDMwaDQ0YzIgMCA0IDIgNCA0djRjMCAyLTIsNCA0IDRKMjhjLTIsMC00LTItNC00di00YzAtMiAyLTQgNC00eiIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg==',

            // Green Avatar
            'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzVjYjg1YyIvPjxwYXRoIGQ9Ik0yNSAzNWMwLTIgMi00IDQtNGg2YzIgMCA0IDIgNCA0djEwYzAgMi0yIDQtNCA0aC02Yy0yIDAtNC0yLTQtNFYzNXptMzYgMGMwLTIgMi00IDQtNGg2YzIgMCA0IDIgNCA0djEwYzAgMi0yIDQtNCA0aC02Yy0yIDAtNC0yLTQtNFYzNXptLTMzIDMwaDQ0YzIgMCA0IDIgNCA0djRjMCAyLTIsNCA0IDRKMjhjLTIsMC00LTItNC00di00YzAtMiAyLTQgNC00eiIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg==',

            // Purple Avatar
            'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2EwMjBmMCIvPjxwYXRoIGQ9Ik0yNSAzNWMwLTIgMi00IDQtNGg2YzIgMCA0IDIgNCA0djEwYzAgMi0yIDQtNCA0aC02Yy0yIDAtNC0yLTQtNFYzNXptMzYgMGMwLTIgMi00IDQtNGg2YzIgMCA0IDIgNCA0djEwYzAgMi0yIDQtNCA0aC02Yy0yIDAtNC0yLTQtNFYzNXptLTMzIDMwaDQ0YzIgMCA0IDIgNCA0djRjMCAyLTIsNCA0IDRKMjhjLTIsMC00LTItNC00di00YzAtMiAyLTQgNC00eiIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg==',
        ];

        return $avatars[array_rand($avatars)];
    }

    // 2. Handle Profile Selection
    public function switchProfile(Request $request, $id)
    {
        // SECURITY: Ensure this profile actually belongs to the user
        // findOrFail will throw 404 if the user tries to hack another person's profile ID
        $profile = Auth::user()->profiles()->findOrFail($id);

        // Store the profile ID in the session for the rest of the app to use
        session(['current_profile_id' => $profile->id]);
        session(['is_kid' => $profile->is_kid]); // Store kid status for filtering

        // Redirect to the main movie browse page
        return redirect()->route('browse.index');
    }
}
