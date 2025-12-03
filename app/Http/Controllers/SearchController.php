<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

/**
 * SearchController
 * 
 * Controller ini menangani fitur pencarian artikel.
 * User bisa mencari artikel berdasarkan judul atau konten.
 * 
 * Fitur utama:
 * - Pencarian artikel berdasarkan keyword
 * - Hanya menampilkan artikel yang sudah dipublikasikan
 * - Hasil pencarian dengan pagination
 * 
 * @package App\Http\Controllers
 */
class SearchController extends Controller
{
    /**
     * Menampilkan hasil pencarian artikel
     * 
     * Method ini memproses pencarian artikel berdasarkan keyword yang diinput user.
     * Pencarian dilakukan pada kolom 'title' dan 'content' menggunakan LIKE query.
     * 
     * Proses yang dilakukan:
     * 1. Ambil keyword pencarian dari request
     * 2. Query artikel yang mengandung keyword di title atau content
     * 3. Filter hanya artikel yang sudah published
     * 4. Tampilkan hasil dengan pagination
     * 
     * @param Request $request Request yang berisi parameter 'query' (keyword pencarian)
     * @return View Halaman homepage dengan hasil pencarian
     */
    public function index(Request $request)
    {
        // STEP 1: Ambil keyword pencarian dari input user
        $query = $request->input('query');
        
        // STEP 2: Query artikel yang mengandung keyword
        // Pencarian dilakukan di kolom 'title' dan 'content'
        // Eager load relasi 'user' dan 'category' untuk menghindari N+1 problem
        $articles = Article::where('title', 'like', "%{$query}%")
                          ->orWhere('content', 'like', "%{$query}%")
                          ->where('status', 'published')  // Hanya artikel published
                          ->with(['user', 'category'])
                          ->latest()
                          ->paginate(9);
        
        // STEP 3: Ambil semua kategori untuk navbar
        $categories = \App\Models\Category::all() ?? collect();
        
        // STEP 4: Tampilkan hasil pencarian di homepage
        // Kirim juga $query untuk ditampilkan di search box
        return view('homepage', compact('articles', 'query', 'categories'));
    }
}
