<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrowseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', [BrowseController::class, 'index'])->name('browse.index');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::middleware('auth')->group(function () {
    // 1. Profile Selection
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
    Route::get('/profiles/create', [ProfileController::class, 'create'])->name('profiles.create');
    Route::post('/profiles', [ProfileController::class, 'store'])->name('profiles.store');
    Route::post('/profiles/switch/{id}', [ProfileController::class, 'switchProfile'])->name('profiles.switch');

    // 2. Main Application
    Route::get('/browse', [BrowseController::class, 'index'])->name('browse.home'); // Alias for /browse
    Route::get('/series', [BrowseController::class, 'series'])->name('browse.series');
    Route::get('/watch/{movie}', [BrowseController::class, 'watch'])->name('browse.watch');
    Route::get('/watch/{movie}/episode/{episode}', [BrowseController::class, 'watch'])->name('browse.watch.episode');
});
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/upload', [AdminController::class, 'index'])->name('admin.upload');
    Route::post('/upload', [AdminController::class, 'store'])->name('admin.store');
});
