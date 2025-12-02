<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/login', function () {
    // Pastikan 'auth.login' adalah nama file Blade kustom Anda (misalnya: resources/views/auth/login.blade.php)
    return view('login'); 
})->middleware('guest')->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')->name('logout');

Route::get('/register', function () {
    // Ganti 'auth.register' jika nama file Blade kustom Anda berbeda
    return view('registview'); 
})->middleware('guest')->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');
