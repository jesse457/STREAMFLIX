<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Show the Login Form
    public function login()
    {
        return view('auth.login');
    }

    // 2. Handle the POST Request
    public function authenticate(Request $request)
    {
        // Validate inputs
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to log the user in
        // 'remember' field is checked if the checkbox is "on"
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect to the Profile Selection screen, not the movies directly
            return redirect()->intended('/profiles');
        }

        // Auth failed
        return back()->withErrors([
            'email' => 'Sorry, we can\'t find an account with this email address. Please try again or create a new account.',
        ])->onlyInput('email');
    }

    // 3. Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
