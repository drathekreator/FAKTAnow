<?php

/**
 * Web Routes
 * 
 * File ini mendefinisikan semua route untuk aplikasi web.
 * Route dikelompokkan berdasarkan:
 * 1. Route publik - Bisa diakses tanpa login
 * 2. Route guest - Hanya untuk yang belum login (login/register)
 * 3. Route authenticated - Harus login
 * 4. Route admin - Hanya untuk role admin
 * 5. Route editor - Hanya untuk role editor
 * 6. Route untuk fitur like dan komentar
 * 
 * Middleware yang digunakan:
 * - guest: Hanya untuk user yang belum login
 * - auth: Harus login
 * - verified: Email harus sudah diverifikasi
 * - role:admin: Hanya untuk admin
 * - role:editor: Hanya untuk editor
 */

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\homePageController;

// =====================================================================
// 1. ROUTE PUBLIK - Bisa diakses tanpa login
// =====================================================================

// Homepage: Menampilkan daftar artikel terpublikasi dengan fitur pencarian
Route::get('/', [homePageController::class, 'index'])->name('home');

// Filter artikel berdasarkan kategori
Route::get('/category/{category:slug}', [homePageController::class, 'byCategory'])->name('category.show');

// Detail artikel: Menampilkan artikel lengkap dengan komentar
Route::get('/article/{article:slug}', [homePageController::class, 'show'])->name('article.show');


// =====================================================================
// 2. ROUTE OTENTIKASI - Hanya untuk guest (belum login)
// =====================================================================

Route::middleware('guest')->group(function () {
    // Halaman login
    Route::get('/login', function () { return view('login'); })->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    
    // Halaman registrasi
    Route::get('/register', function () { return view('registview'); })->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});


// =====================================================================
// 3. ROUTE YANG MEMERLUKAN OTENTIKASI - Harus login
// =====================================================================

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard default: Redirect member ke homepage
    // Admin dan Editor punya dashboard sendiri, member tidak perlu dashboard
    Route::get('/dashboard', function () { 
        // Jika user adalah member, redirect ke homepage
        if (Auth::user()->role === 'member' || Auth::user()->role === 'user') {
            return redirect()->route('home');
        }
        // Jika admin atau editor, tampilkan dashboard default (fallback)
        return view('dashboard'); 
    })->name('dashboard');

    // ===== ROUTE PROFILE & LOGOUT =====
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


    // =================================================================
    // 4. ROUTE KHUSUS ADMIN - Hanya bisa diakses oleh role 'admin'
    // =================================================================
    Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
        
        // Dashboard admin: Menampilkan semua user dan artikel
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        
        // Manajemen user
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::patch('/admin/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.updateRole');

        // Manajemen artikel
        Route::delete('/admin/articles/{article}', [AdminController::class, 'destroyArticle'])->name('admin.articles.destroy');
        
        // Review dan moderasi artikel
        Route::get('/admin/articles/review', [ArticleController::class, 'reviewIndex'])->name('admin.review.index');
        Route::put('/admin/articles/{article}/status', [ArticleController::class, 'updateStatus'])->name('admin.articles.updateStatus');
        
        // Admin bisa edit semua artikel (termasuk yang sudah published)
        // Menggunakan route yang sama dengan editor untuk konsistensi
        Route::get('/editor/articles/{article}/edit', [ArticleController::class, 'edit'])->name('admin.articles.edit');
        Route::put('/editor/articles/{article}', [ArticleController::class, 'update'])->name('admin.articles.update');
    });


    // =================================================================
    // 5. ROUTE KHUSUS EDITOR - Hanya bisa diakses oleh role 'editor'
    // =================================================================
    Route::middleware('role:editor')->group(function () { 
        
        // Dashboard editor: Menampilkan artikel milik editor yang sedang login
        Route::get('/editor/dashboard', [ArticleController::class, 'index'])->name('editor.dashboard');
        
        // CRUD Resource untuk Artikel
        // URI: /editor/articles/{id}
        // Nama route: articles.create, articles.store, articles.edit, articles.update, articles.destroy
        Route::resource('editor/articles', ArticleController::class)
            ->except(['index', 'show'])  // Index dan show sudah didefinisikan terpisah
            ->names('articles');         // Gunakan nama 'articles' untuk konsistensi
    });
    
    // =================================================================
    // 6. ROUTE KOMENTAR & LIKE - Untuk semua user yang sudah login
    // =================================================================
    
    // Menambahkan komentar pada artikel
    Route::post('/article/{article:slug}/comment', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    
    // Toggle like/unlike artikel
    Route::post('/article/{article:slug}/like', [\App\Http\Controllers\LikeController::class, 'toggle'])->name('articles.like');
});