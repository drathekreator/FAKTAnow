<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

/**
 * LikeController
 * 
 * Controller ini menangani fitur like/unlike artikel.
 * User yang sudah login bisa memberikan like pada artikel,
 * dan bisa membatalkan like (unlike) jika sudah pernah like sebelumnya.
 * 
 * Fitur utama:
 * - Toggle like/unlike artikel
 * - Satu user hanya bisa like satu kali per artikel
 * - Like bisa dibatalkan kapan saja
 * 
 * @package App\Http\Controllers
 */
class LikeController extends Controller
{
    /**
     * Toggle like/unlike pada artikel
     * 
     * Method ini mengimplementasikan fitur toggle like:
     * - Jika user belum like artikel ini, maka tambahkan like
     * - Jika user sudah like artikel ini, maka hapus like (unlike)
     * 
     * Proses yang dilakukan:
     * 1. Cek apakah user sudah pernah like artikel ini
     * 2. Jika sudah, hapus like (unlike)
     * 3. Jika belum, tambahkan like baru
     * 
     * @param Article $article Model artikel yang akan di-like/unlike (Route Model Binding)
     * @return RedirectResponse Redirect kembali ke halaman sebelumnya dengan pesan
     */
    public function toggle(Article $article): RedirectResponse
    {
        // Ambil user yang sedang login
        $user = Auth::user();
        
        // STEP 1: Cek apakah user sudah pernah like artikel ini
        // Query ke tabel likes berdasarkan user_id dan article_id
        $existingLike = Like::where('user_id', $user->id)
                           ->where('article_id', $article->id)
                           ->first();
        
        // STEP 2: Toggle logic
        if ($existingLike) {
            // Jika sudah pernah like, hapus like (UNLIKE)
            $existingLike->delete();
            return back()->with('success', 'Like dihapus.');
        } else {
            // Jika belum pernah like, tambahkan like baru (LIKE)
            Like::create([
                'user_id' => $user->id,
                'article_id' => $article->id,
            ]);
            return back()->with('success', 'Artikel disukai!');
        }
    }
}
