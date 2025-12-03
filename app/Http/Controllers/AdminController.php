<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;      // Import Model User untuk manajemen pengguna
// use App\Models\Article; // Import Model Article Anda (misalnya jika nama modelnya Article)

class AdminController extends Controller
{
    /**
     * Menampilkan Admin Dashboard dengan daftar semua pengguna dan artikel.
     * Metode ini dipanggil oleh Route::get('/admin/dashboard').
     */
    public function index()
    {
        // 1. Ambil data Pengguna
        // Mengambil semua user kecuali user yang sedang login saat ini (auth()->id())
        // untuk mencegah admin menghapus dirinya sendiri secara tidak sengaja.
        $users = User::where('id', '!=', auth()->id())->get();

        // 2. Ambil data Artikel (Ganti 'Article' dengan nama model Anda)
        // Anda bisa tambahkan paginasi (paginate(10)) jika datanya banyak.
        // $articles = Article::latest()->get(); 
        
        // Catatan: Jika Anda belum punya model Article, baris di atas bisa dikomentari dulu.
        $articles = []; // Placeholder jika Model Article belum siap

        // Tampilkan view dashboard admin dan kirim data
        return view('admin.dashboard', [
            'users' => $users,
            'articles' => $articles,
        ]);
    }

    /**
     * Menghapus pengguna dari database.
     * Metode ini dipanggil oleh Route::delete('/admin/users/{user}').
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroyUser(User $user) // Route Model Binding digunakan di sini
    {
        // Pencegahan: Larang admin menghapus admin lain
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat menghapus pengguna dengan peran Admin.');
        }
        
        // Hapus pengguna
        $user->delete();

        return back()->with('success', 'Pengguna ' . $user->name . ' berhasil dihapus.');
    }
}