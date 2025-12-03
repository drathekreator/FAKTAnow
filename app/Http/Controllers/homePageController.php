<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;    
use App\Models\Category;   

class HomePageController extends Controller 
{
    /**
     * Menampilkan halaman utama, mendukung fitur pencarian.
     * Mengirimkan $categories dan $articles ke view.
     */
    public function index(Request $request)
    {
        try {
            // 1. Ambil Kategori untuk Navbar (mengatasi Undefined variable $categories)
            // Menggunakan withDefault() pada collections jika hasil query kosong atau gagal
            $categories = Category::all() ?? collect(); 

            // 2. Query Logika Artikel
            // Memastikan eager load relasi user, category, dan likes untuk menghindari N+1 problem
            $query = Article::with(['user', 'category'])
                            ->withCount('likes')
                            ->where('status', 'published');

            // Logika Pencarian: Pastikan hanya berjalan jika ada string pencarian yang valid
            $searchTerm = $request->input('search'); // Gunakan input() untuk sanitasi yang lebih baik
            if (!empty($searchTerm)) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('content', 'like', '%' . $searchTerm . '%');
                });
            }

            // Ambil data dengan Pagination
            $articles = $query->latest()->paginate(9);

            // 3. Kirim variabel ke View
            return view('homepage', compact('categories', 'articles'));
        
        } catch (\Exception $e) {
            // Catat log error database
            \Log::error('Database Error in HomePageController@index: ' . $e->getMessage());
            
            // Definisikan variabel sebagai array kosong jika terjadi error database
            $categories = collect(); 
            // PENTING: Gunakan LengthAwarePaginator untuk membuat paginator kosong
            $articles = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(), 0, 9, 1
            );

            // Tampilkan view error yang bersih, atau kembalikan ke homepage dengan data kosong.
            return view('homepage', compact('categories', 'articles'))->with('error', 'Terjadi kesalahan saat memuat data. Silakan coba lagi nanti.');
        }
    }
    
    /**
     * Menampilkan artikel berdasarkan kategori tertentu.
     */
    public function byCategory(Category $category)
    {
        try {
            // 1. Ambil data kategori untuk navbar
            $categories = Category::all() ?? collect();

            // 2. Ambil artikel HANYA dari kategori yang dipilih
            $articles = $category->articles()
                                 ->with('user')
                                 ->withCount('likes')
                                 ->where('status', 'published')
                                 ->latest()
                                 ->paginate(9);

            // 3. Gunakan view yang sama ('homepage')
            return view('homepage', compact('categories', 'articles'));
        
        } catch (\Exception $e) {
            \Log::error('Database Error in HomePageController@byCategory: ' . $e->getMessage());
            
            // Definisikan variabel sebagai array kosong jika terjadi error database
            $categories = collect(); 
            $articles = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(), 0, 9, 1
            );
            
            return view('homepage', compact('categories', 'articles'))->with('error', 'Terjadi kesalahan saat memuat kategori. Silakan coba lagi nanti.');
        }
    }
    
    /**
     * Menampilkan detail artikel dan komentar
     */
    public function show(Article $article)
    {
        try {
            // Increment views
            $article->increment('views');
            
            // Ambil kategori untuk navbar
            $categories = Category::all() ?? collect();
            
            // Ambil semua komentar (tidak perlu approval)
            $comments = $article->comments()
                               ->with('user')
                               ->latest()
                               ->get();
            
            // Load likes count
            $article->loadCount('likes');
            
            return view('detailedpage', compact('article', 'categories', 'comments'));
        
        } catch (\Exception $e) {
            \Log::error('Database Error in HomePageController@show: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Artikel tidak ditemukan.');
        }
    }
}