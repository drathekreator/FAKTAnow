<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Article; // WAJIB: Import Model Article Anda
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    /**
     * Menampilkan Admin Dashboard dengan daftar semua pengguna dan artikel.
     * @return View
     */
    public function index(): View
    {
        // 1. Ambil data Pengguna
        $users = User::where('id', '!=', Auth::id())->get();

        // 2. Ambil data Artikel (Admin melihat SEMUA)
        // Eager load relasi 'user' (penulis) untuk ditampilkan di tabel
        $articles = Article::with('user')->latest()->get(); 
        
        // Tampilkan view dashboard admin dan kirim data
        return view('admin.dashboard', compact('users', 'articles'));
    }

    /**
     * Menghapus pengguna dari database.
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyUser(User $user): RedirectResponse
    {
        // Pencegahan: Larang admin menghapus admin lain
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat menghapus pengguna dengan peran Admin.');
        }
        
        // Hapus pengguna
        $user->delete();

        return back()->with('success', 'Pengguna ' . $user->name . ' berhasil dihapus.');
    }
    
    /**
     * Menghapus artikel dari database oleh Admin.
     * Menggunakan Model Binding untuk menemukan artikel berdasarkan slug.
     * @param \App\Models\Article $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyArticle(Article $article): RedirectResponse
    {
        // 1. Hapus file thumbnail dari storage (jika ada)
        if ($article->thumbnail_url) {
            // Konversi URL storage ke path yang bisa dihapus ('/storage/...' -> 'public/...')
            $pathToDelete = Str::replaceFirst('/storage/', 'public/', $article->thumbnail_url);
            Storage::delete($pathToDelete);
        }
        
        // 2. Hapus data artikel
        $article->delete();

        return back()->with('success', "Artikel '{$article->title}' berhasil dihapus.");
    }
}