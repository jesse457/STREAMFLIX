<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // 1. Show the Registration Form
    public function create()
    {
        return view('auth.register');
    }

    // 2. Handle the Logic
    public function store(Request $request)
    {
        // Validate specifically for Netflix style (Email & Password)
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // Expects password_confirmation field
        ]);

        // Create the User
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // We don't set subscription details here yet, keeping it simple for resume
        ]);

        // Auto Login
        Auth::login($user);

        // Redirect to Profiles
        // Since this is a new user, your ProfileController logic will see 0 profiles
        // and force them to the "Create Profile" screen automatically.
        return redirect()->route('profiles.index');
    }
}
