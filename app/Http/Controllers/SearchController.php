<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //
    public function index(Request $request){
        $query = $request->input('query');
        $news = News::where('title', 'likes', '%query%')->get();
        return view('search-result', compact('articles', 'query'));
    }
    

}
