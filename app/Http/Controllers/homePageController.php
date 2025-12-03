<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;   // <-- Pastikan Model ini di-import
use App\Models\Category;  // <-- Pastikan Model ini di-import

class homePageController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Kategori
        $categories = Category::all();

        // 2. Ambil Artikel (Query Logika)
        $query = Article::with(['user', 'category']) // Eager load user & category
                        ->where('status', 'published');

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        // Ambil data (sesuai log error kamu, sepertinya kamu pakai paginate 4)
        $articles = $query->latest()->paginate(9); // Saya sarankan 9 agar grid 3x3 rapi

        // 3. PENTING: Kirim variabel 'articles' ke view menggunakan compact
        return view('homepage', compact('categories', 'articles'));
    }
    public function byCategory(Category $category){
        // 1. Ambil data kategori untuk navbar (tetap diperlukan)
        $categories = Category::all();

    // 2. Ambil artikel HANYA dari kategori yang dipilih
        $articles = $category->articles()
                             ->with('user')
                             ->where('status', 'published')
                             ->latest()
                             ->paginate(9);

    // 3. Gunakan view yang sama ('homepage')
        return view('homepage', compact('categories', 'articles'));
    }
}
