<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Article;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

/**
 * AdminController
 * 
 * Controller ini menangani semua operasi yang hanya bisa dilakukan oleh Admin.
 * 
 * Fitur utama:
 * - Dashboard admin untuk melihat semua user dan artikel
 * - Manajemen user (hapus user, update role)
 * - Manajemen artikel (hapus artikel dari semua penulis)
 * - Moderasi konten
 * 
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * Menampilkan Admin Dashboard dengan daftar semua pengguna dan artikel
     * 
     * Method ini menampilkan halaman dashboard admin yang berisi:
     * - Daftar semua user (kecuali admin yang sedang login)
     * - Daftar semua artikel dari semua penulis
     * 
     * Admin dapat melihat dan mengelola semua data di sistem dari halaman ini.
     * 
     * @return View Halaman dashboard admin
     */
    public function index(): View
    {
        // STEP 1: Ambil data semua user kecuali admin yang sedang login
        // Ini untuk menghindari admin menghapus dirinya sendiri
        $users = User::where('id', '!=', Auth::id())->get();

        // STEP 2: Ambil data semua artikel dari semua penulis
        // Eager load relasi 'user' untuk menampilkan nama penulis tanpa query tambahan (menghindari N+1 problem)
        // Diurutkan dari yang terbaru
        $articles = Article::with('user')->latest()->get(); 

        // STEP 3: Kirim data ke view dashboard admin
        return view('admin.dashboard', [
            'users' => $users,
            'articles' => $articles
        ]);
    }

    /**
     * Menghapus pengguna dari database
     * 
     * Method ini memungkinkan admin untuk menghapus user dari sistem.
     * Admin tidak bisa menghapus admin lain untuk keamanan.
     * 
     * @param User $user Model user yang akan dihapus (Route Model Binding)
     * @return RedirectResponse Redirect kembali dengan pesan sukses/error
     */
    public function destroyUser(User $user): RedirectResponse
    {
        // Pencegahan: Larang admin menghapus admin lain
        // Ini untuk keamanan agar tidak ada admin yang menghapus admin lain
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak dapat menghapus pengguna dengan peran Admin.');
        }
        
        // Hapus user dari database
        // Ini juga akan menghapus relasi terkait (artikel, komentar, likes) jika ada cascade delete
        $user->delete();

        return back()->with('success', 'Pengguna ' . $user->name . ' berhasil dihapus.');
    }

    /**
     * Mengupdate role/peran user
     * 
     * Method ini memungkinkan admin untuk mengubah role user:
     * - admin: Akses penuh ke semua fitur
     * - editor: Bisa membuat dan mengelola artikel
     * - user: Hanya bisa membaca, like, dan komentar
     * 
     * @param Request $request Data role baru dari form
     * @param User $user Model user yang akan diupdate rolenya
     * @return RedirectResponse Redirect kembali dengan pesan sukses
     */
    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        // Validasi: Role harus salah satu dari 3 pilihan yang valid
        $request->validate([
            'role' => 'required|in:admin,editor,user',
        ]);
        
        // Update role user di database
        $user->update([
            'role' => $request->role,
        ]);

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', "Role {$user->name} sudah berhasil diupdate menjadi {$request->role}");
    }
    
    /**
     * Menghapus artikel dari database oleh Admin
     * 
     * Method ini memungkinkan admin untuk menghapus artikel dari penulis manapun.
     * Berbeda dengan ArticleController@destroy yang hanya bisa menghapus artikel sendiri,
     * admin bisa menghapus semua artikel untuk moderasi konten.
     * 
     * Proses yang dilakukan:
     * 1. Hapus file thumbnail dari storage
     * 2. Hapus data artikel dari database
     * 
     * @param Article $article Model artikel yang akan dihapus (Route Model Binding)
     * @return RedirectResponse Redirect kembali dengan pesan sukses
     */
    public function destroyArticle(Article $article): RedirectResponse
    {
        // STEP 1: Hapus file thumbnail dari storage (jika ada)
        if ($article->thumbnail_url) {
            try {
                // Konversi URL menjadi path: /storage/thumbnails/file.jpg -> thumbnails/file.jpg
                $path = Str::replaceFirst('/storage/', '', $article->thumbnail_url);
                Storage::disk('public')->delete($path);
                
                // Log untuk audit trail
                \Log::info('Admin deleted article thumbnail', [
                    'article_id' => $article->id,
                    'path' => $path
                ]);
            } catch (\Exception $e) {
                // Log error tapi tetap lanjutkan penghapusan artikel
                \Log::error('Failed to delete thumbnail by admin', [
                    'error' => $e->getMessage(),
                    'article_id' => $article->id
                ]);
            }
        }
        
        // STEP 2: Hapus data artikel dari database
        $article->delete();

        // Redirect kembali dengan pesan sukses
        return back()->with('success', "Artikel '{$article->title}' berhasil dihapus.");
    }
}