<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\homePageController;
use App\Http\Controllers\AuthController;

Route::get('/', [homePageController::class, 'index']);

// ---------- LOGIN ----------
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// ---------- REGISTER ----------
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// ---------- LOGOUT ----------
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
