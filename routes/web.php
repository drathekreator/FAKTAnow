<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\homePageController;

// ---------------------------------------------------------------------
// 1. ROUTE PUBLIK DAN GUEST
// ---------------------------------------------------------------------

Route::get('/', function () {
    return view('homepage');
});

// Route OTENTIKASI KUSTOM
Route::middleware('guest')->group(function () {
    Route::get('/login', function () { return view('login'); })->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', function () { return view('registview'); })->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});


// ---------------------------------------------------------------------
// 2. ROUTE YANG MEMERLUKAN OTENTIKASI (Dilindungi Middleware 'auth')
// ---------------------------------------------------------------------

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard User Default (Jika ada)
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    // Route Profile & Logout
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


    // ---------------------------------------------------------------------
    // 3. ROUTE KHUSUS (ROLE: ADMIN)
    // ---------------------------------------------------------------------
    Route::middleware('role:admin')->group(function () {
        
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

        // BARIS BARU: Route untuk menghapus artikel oleh Admin (memanggil destroyArticle di AdminController)
        Route::delete('/admin/articles/{article}', [AdminController::class, 'destroyArticle'])->name('admin.articles.destroy');

        Route::get('/admin/articles/review', [ArticleController::class, 'reviewIndex'])->name('admin.review.index');
        Route::put('/admin/articles/{article}/status', [ArticleController::class, 'updateStatus'])->name('admin.articles.updateStatus');
    });


    // ---------------------------------------------------------------------
    // 4. ROUTE KHUSUS (ROLE: EDITOR)
    // ---------------------------------------------------------------------
    Route::middleware('role:editor')->group(function () { 
        
        // A. DEFINISI DASHBOARD AUTHOR (URL yang Anda inginkan)
        Route::get('/editor/dashboard', [ArticleController::class, 'index'])->name('editor.dashboard');
        
        // B. CRUD Resource untuk Artikel (PERBAIKAN DI SINI)
        // KITA UBAH URI DARI 'articles' MENJADI 'editor/articles'
        Route::resource('editor/articles', ArticleController::class)
            ->except(['index', 'show'])
            // KITA PERTAHANKAN NAMA ROUTE 'articles' AGAR LINK TOMBOL TIDAK RUSAK
            ->names('articles');
    });
});