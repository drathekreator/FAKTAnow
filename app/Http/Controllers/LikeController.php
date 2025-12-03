<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LikeController extends Controller
{
    /**
     * Toggle like pada artikel
     */
    public function toggle(Article $article): RedirectResponse
    {
        $user = Auth::user();
        
        // Check if user already liked
        $existingLike = Like::where('user_id', $user->id)
                           ->where('article_id', $article->id)
                           ->first();
        
        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            return back()->with('success', 'Like dihapus.');
        } else {
            // Like
            Like::create([
                'user_id' => $user->id,
                'article_id' => $article->id,
            ]);
            return back()->with('success', 'Artikel disukai!');
        }
    }
}
