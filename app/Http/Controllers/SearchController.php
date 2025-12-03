<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //
    public function index(Request $request){
        $query = $request->input('query');
        $articles = Article::where('title', 'like', "%{$query}%")
                          ->orWhere('content', 'like', "%{$query}%")
                          ->where('status', 'published')
                          ->with(['user', 'category'])
                          ->latest()
                          ->paginate(9);
        
        $categories = \App\Models\Category::all() ?? collect();
        
        return view('homepage', compact('articles', 'query', 'categories'));
    }
    

}
