<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

/**
 * CommentController
 * 
 * Controller ini menangani semua operasi terkait komentar artikel.
 * 
 * Fitur utama:
 * - User bisa menambahkan komentar pada artikel
 * - Admin bisa memoderasi komentar (approve/delete)
 * - Komentar langsung disetujui (auto-approved)
 * 
 * @package App\Http\Controllers
 */
class CommentController extends Controller
{
    /**
     * Menyimpan komentar baru pada artikel
     * 
     * Method ini memproses form komentar yang dikirim oleh user.
     * Komentar langsung disetujui (auto-approved) tanpa perlu moderasi admin.
     * 
     * @param Request $request Data komentar dari form
     * @param Article $article Model artikel yang dikomentari (Route Model Binding)
     * @return RedirectResponse Redirect kembali ke halaman artikel dengan pesan sukses
     */
    public function store(Request $request, Article $article): RedirectResponse
    {
        // Validasi input: Komentar wajib diisi, maksimal 1000 karakter
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        
        // Buat komentar baru di database
        Comment::create([
            'user_id' => Auth::id(),              // ID user yang sedang login
            'article_id' => $article->id,         // ID artikel yang dikomentari
            'content' => $validated['content'],   // Isi komentar
            'is_approved' => true,                // Komentar langsung disetujui
        ]);
        
        // Redirect kembali ke halaman artikel dengan pesan sukses
        return back()->with('success', 'Komentar Anda berhasil dikirim.');
    }
    
    /**
     * Admin: Menyetujui komentar
     * 
     * Method ini memungkinkan admin untuk menyetujui komentar yang sebelumnya
     * ditahan untuk moderasi. Setelah disetujui, komentar akan tampil di artikel.
     * 
     * @param Comment $comment Model komentar yang akan disetujui (Route Model Binding)
     * @return RedirectResponse Redirect kembali dengan pesan sukses
     */
    public function approve(Comment $comment): RedirectResponse
    {
        // Update status komentar menjadi approved
        $comment->update(['is_approved' => true]);
        
        return back()->with('success', 'Komentar berhasil disetujui.');
    }
    
    /**
     * Admin: Menolak/menghapus komentar
     * 
     * Method ini memungkinkan admin untuk menghapus komentar yang melanggar
     * aturan atau tidak pantas. Komentar akan dihapus permanen dari database.
     * 
     * @param Comment $comment Model komentar yang akan dihapus (Route Model Binding)
     * @return RedirectResponse Redirect kembali dengan pesan sukses
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        // Hapus komentar dari database
        $comment->delete();
        
        return back()->with('success', 'Komentar berhasil dihapus.');
    }
    
    /**
     * Admin: Menampilkan daftar komentar yang perlu dimoderasi
     * 
     * Method ini menampilkan halaman moderasi komentar untuk admin.
     * Menampilkan semua komentar yang belum disetujui (is_approved = false)
     * dengan pagination 20 item per halaman.
     * 
     * @return View Halaman moderasi komentar untuk admin
     */
    public function moderate()
    {
        // Ambil komentar yang belum disetujui
        // Eager load relasi 'user' dan 'article' untuk menampilkan info lengkap
        $comments = Comment::with(['user', 'article'])
                          ->where('is_approved', false)
                          ->latest()
                          ->paginate(20);
        
        return view('admin.comments.moderate', compact('comments'));
    }
}
