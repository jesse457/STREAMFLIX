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
            'profiles' => $profiles
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

    // 3. Save the New Profile
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20',
        ]);

        Auth::user()->profiles()->create([
            'name' => $request->name,
            // Assign a random Netflix-style avatar based on name length (simulating randomness)
            'avatar' => $this->getRandomAvatar(),
            'is_kid' => $request->has('is_kid'), // Checkbox logic
        ]);

        return redirect()->route('profiles.index');
    }

    // Helper to get random avatars
    private function getRandomAvatar()
    {
        $avatars = [
            'https://occ-0-1723-1722.1.nflxso.net/dnm/api/v6/K6hjPJd6cR6FpVELC5Pd6ovAW4k/AAAABfNXUMVXGhnCZwPI1SghnGpmUgqS_J-owMff-jigqn8onK9jlzu16dbqFRC73tnvtpJeNPIc-c8c4C_i3lPpH1g.png?r=fcd',
            'https://occ-0-1723-1722.1.nflxso.net/dnm/api/v6/K6hjPJd6cR6FpVELC5Pd6ovAW4k/AAAABY5cwIbM7shRfcXPHxb8Qy-XYV_frkimbkckA25fpv33xFqT139V07t_xM9F8aZ5_5v-0S5r2f0I.png?r=fcd',
            'https://occ-0-1723-1722.1.nflxso.net/dnm/api/v6/K6hjPJd6cR6FpVELC5Pd6ovAW4k/AAAABZ8d3P3uQ9oF0-C_7JpS5iC5-y9F3r5_5v-0S5r2f0I.png?r=fcd',
            'https://occ-0-1723-1722.1.nflxso.net/dnm/api/v6/K6hjPJd6cR6FpVELC5Pd6ovAW4k/AAAABd3Ie_J3xI1e1i7-75j9o6a00S5r2f0I.png?r=fcd'
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
