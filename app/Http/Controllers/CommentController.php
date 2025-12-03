<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    /**
     * Menyimpan komentar baru
     */
    public function store(Request $request, Article $article): RedirectResponse
    {
        // Validasi input
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        
        // Buat komentar baru (langsung approved)
        Comment::create([
            'user_id' => Auth::id(),
            'article_id' => $article->id,
            'content' => $validated['content'],
            'is_approved' => true, // Komentar langsung disetujui
        ]);
        
        return back()->with('success', 'Komentar Anda berhasil dikirim.');
    }
    
    /**
     * Admin: Menyetujui komentar
     */
    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update(['is_approved' => true]);
        return back()->with('success', 'Komentar berhasil disetujui.');
    }
    
    /**
     * Admin: Menolak/menghapus komentar
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();
        return back()->with('success', 'Komentar berhasil dihapus.');
    }
    
    /**
     * Admin: Menampilkan daftar komentar yang perlu dimoderasi
     */
    public function moderate()
    {
        $comments = Comment::with(['user', 'article'])
                          ->where('is_approved', false)
                          ->latest()
                          ->paginate(20);
        
        return view('admin.comments.moderate', compact('comments'));
    }
}
