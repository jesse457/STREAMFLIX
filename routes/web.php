<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VideoController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;


Route::get('/', [VideoController::class, 'index'])->name('videos.index');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::middleware('auth')->group(function () {

    // 1. Profile Selection (The "Who is watching?" screen)
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
    Route::get('/profiles/create', [ProfileController::class, 'create'])->name('profiles.create');
    Route::post('/profiles', [ProfileController::class, 'store'])->name('profiles.store');
    Route::post('/profiles/switch/{id}', [ProfileController::class, 'switchProfile'])->name('profiles.switch');

    // 2. Main Application (Requires a Profile to be selected)
    // We check session('current_profile_id') inside the controller or a middleware
    Route::get('/browse', [HomeController::class, 'index'])->name('browse.index');
    Route::get('/watch/{id}', [HomeController::class, 'watch'])->name('watch');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/upload', [AdminController::class, 'index'])->name('admin.upload');
    Route::post('/upload', [AdminController::class, 'store'])->name('admin.store');
});
