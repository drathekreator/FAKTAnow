<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;    
use App\Models\Category;   

/**
 * HomePageController
 * 
 * Controller ini menangani halaman publik yang bisa diakses semua pengunjung.
 * 
 * Fitur utama:
 * - Menampilkan homepage dengan daftar artikel terpublikasi
 * - Fitur pencarian artikel berdasarkan judul dan konten
 * - Filter artikel berdasarkan kategori
 * - Menampilkan detail artikel dengan komentar
 * - Increment views counter saat artikel dibuka
 * 
 * @package App\Http\Controllers
 */
class HomePageController extends Controller 
{
    /**
     * Menampilkan halaman utama dengan daftar artikel dan fitur pencarian
     * 
     * Method ini menampilkan homepage yang berisi:
     * - Daftar kategori untuk navbar
     * - Daftar artikel yang sudah dipublikasikan
     * - Fitur pencarian artikel berdasarkan judul dan konten
     * - Pagination 9 artikel per halaman
     * 
     * Error handling: Jika terjadi error database, tampilkan halaman dengan data kosong
     * 
     * @param Request $request Request yang berisi parameter pencarian (opsional)
     * @return View Halaman homepage dengan data artikel dan kategori
     */
    public function index(Request $request)
    {
        try {
            // STEP 1: Ambil semua kategori untuk ditampilkan di navbar
            // Gunakan collect() sebagai fallback jika query gagal
            $categories = Category::all() ?? collect(); 

            // STEP 2: Query artikel yang sudah dipublikasikan
            // Eager load relasi untuk menghindari N+1 problem:
            // - 'user': Untuk menampilkan nama penulis
            // - 'category': Untuk menampilkan nama kategori
            // - withCount('likes'): Untuk menghitung jumlah likes
            $query = Article::with(['user', 'category'])
                            ->withCount('likes')
                            ->where('status', 'published');

            // STEP 3: Logika Pencarian (jika ada parameter 'search')
            $searchTerm = $request->input('search');
            if (!empty($searchTerm)) {
                // Cari di kolom title dan content menggunakan LIKE
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('content', 'like', '%' . $searchTerm . '%');
                });
            }

            // STEP 4: Ambil data dengan pagination (9 artikel per halaman)
            // Diurutkan dari yang terbaru
            $articles = $query->latest()->paginate(9);

            // STEP 5: Kirim data ke view
            return view('homepage', compact('categories', 'articles'));
        
        } catch (\Exception $e) {
            // Error Handling: Jika terjadi error database
            
            // Log error untuk debugging
            \Log::error('Database Error in HomePageController@index: ' . $e->getMessage());
            
            // Buat data kosong untuk ditampilkan
            $categories = collect(); 
            
            // Buat paginator kosong agar tidak error di view
            $articles = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),  // Data kosong
                0,          // Total items
                9,          // Items per page
                1           // Current page
            );

            // Tampilkan view dengan data kosong dan pesan error
            return view('homepage', compact('categories', 'articles'))
                   ->with('error', 'Terjadi kesalahan saat memuat data. Silakan coba lagi nanti.');
        }
    }
    
    /**
     * Menampilkan artikel berdasarkan kategori tertentu
     * 
     * Method ini menampilkan halaman homepage yang difilter berdasarkan kategori.
     * Hanya menampilkan artikel dari kategori yang dipilih.
     * 
     * @param Category $category Model kategori yang dipilih (Route Model Binding)
     * @return View Halaman homepage dengan artikel dari kategori tertentu
     */
    public function byCategory(Category $category)
    {
        try {
            // STEP 1: Ambil semua kategori untuk navbar
            $categories = Category::all() ?? collect();

            // STEP 2: Ambil artikel HANYA dari kategori yang dipilih
            // Menggunakan relasi articles() dari model Category
            // Eager load 'user' untuk menampilkan nama penulis
            // withCount('likes') untuk menghitung jumlah likes
            $articles = $category->articles()
                                 ->with('user')
                                 ->withCount('likes')
                                 ->where('status', 'published')
                                 ->latest()
                                 ->paginate(9);

            // STEP 3: Gunakan view yang sama dengan homepage
            return view('homepage', compact('categories', 'articles'));
        
        } catch (\Exception $e) {
            // Error Handling: Jika terjadi error database
            \Log::error('Database Error in HomePageController@byCategory: ' . $e->getMessage());
            
            // Buat data kosong
            $categories = collect(); 
            $articles = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(), 0, 9, 1
            );
            
            // Tampilkan view dengan data kosong dan pesan error
            return view('homepage', compact('categories', 'articles'))
                   ->with('error', 'Terjadi kesalahan saat memuat kategori. Silakan coba lagi nanti.');
        }
    }
    
    /**
     * Menampilkan detail artikel lengkap dengan komentar
     * 
     * Method ini menampilkan halaman detail artikel yang berisi:
     * - Konten artikel lengkap
     * - Informasi penulis dan kategori
     * - Jumlah views dan likes
     * - Daftar komentar
     * - Form untuk menambah komentar (jika user sudah login)
     * 
     * Setiap kali artikel dibuka, views counter akan bertambah 1.
     * 
     * @param Article $article Model artikel yang akan ditampilkan (Route Model Binding)
     * @return View|RedirectResponse Halaman detail artikel atau redirect jika error
     */
    public function show(Article $article)
    {
        try {
            // STEP 1: Increment views counter
            // Setiap kali artikel dibuka, tambahkan 1 ke kolom 'views'
            $article->increment('views');
            
            // STEP 2: Ambil semua kategori untuk navbar
            $categories = Category::all() ?? collect();
            
            // STEP 3: Ambil semua komentar artikel ini
            // Eager load relasi 'user' untuk menampilkan nama pemberi komentar
            // Diurutkan dari yang terbaru
            $comments = $article->comments()
                               ->with('user')
                               ->latest()
                               ->get();
            
            // STEP 4: Load jumlah likes untuk artikel ini
            $article->loadCount('likes');
            
            // STEP 5: Tampilkan halaman detail artikel
            return view('detailedpage', compact('article', 'categories', 'comments'));
        
        } catch (\Exception $e) {
            // Error Handling: Jika artikel tidak ditemukan atau error database
            \Log::error('Database Error in HomePageController@show: ' . $e->getMessage());
            
            // Redirect ke homepage dengan pesan error
            return redirect()->route('home')->with('error', 'Artikel tidak ditemukan.');
        }
    }
}