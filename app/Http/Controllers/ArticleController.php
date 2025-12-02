<?php

namespace App\Http\Controllers;

use App\Models\Article; // Ganti dengan Model Article Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage; // Wajib di-import
use Illuminate\Support\Str; // Wajib di-import untuk slug

class ArticleController extends Controller
{
    /**
     * Menampilkan daftar semua artikel milik penulis yang sedang login.
     * Ini juga berfungsi sebagai Dashboard Editor.
     * @return View
     */
    public function index(): View
    {
        // Ambil ID user yang sedang login
        $userId = Auth::id();

        // Ambil hanya artikel yang dibuat oleh user ini
        $articles = Article::where('user_id', $userId)
                           ->latest()
                           ->get();

        // **PERBAIKAN KRITIS:** Memanggil view 'editor.dashboard'
        return view('editor.dashboard', compact('articles'));
    }

    /**
     * Menampilkan formulir untuk membuat artikel baru.
     * @return View
     */
    public function create(): View
    {
        return view('editor.create');
    }

    /**
     * Menyimpan artikel baru yang dibuat.
     * Metode ini sekarang menangani SLUG dan UPLOAD FILE.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Data, termasuk file thumbnail
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'nullable|string|unique:articles,slug', // Validasi slug unik (jika diisi manual)
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        // 2. Pembuatan Slug Otomatis
        // Jika slug tidak diisi manual, buat dari title.
        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['slug'] = $slug;

        // 3. Penanganan Upload File Thumbnail
        $validated['thumbnail_url'] = null;
        if ($request->hasFile('thumbnail_file')) {
            // Simpan file ke direktori 'public/thumbnails'
            $path = $request->file('thumbnail_file')->store('public/thumbnails');
            
            // Simpan URL relatif ke database (menggunakan Storage::url)
            $validated['thumbnail_url'] = Storage::url($path);
        }
        
        // 4. Tambahkan user_id dan status default
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'draft'; // Status default
        
        // 5. Simpan data
        Article::create($validated);

        return redirect()->route('editor.dashboard')
                         ->with('success', 'Artikel berhasil dibuat dan menunggu tinjauan.');
    }

    /**
     * Menampilkan artikel tertentu. (Untuk publik/show, biasanya di Controller terpisah)
     * Untuk Editor, ini bisa diabaikan atau digunakan sebagai preview.
     * @param Article $article
     * @return View
     */
    public function show(Article $article): View
    {
        // Otorisasi sederhana: hanya pemilik yang boleh melihat.
        abort_if($article->user_id !== Auth::id(), 403); 
        
        return view('articles.show', compact('article'));
    }

    /**
     * Menampilkan formulir untuk mengedit artikel.
     * @param Article $article
     * @return View
     */
    public function edit(Article $article): View
    {
        // OTORISASI: Pastikan editor hanya bisa mengedit artikelnya sendiri
        abort_if($article->user_id !== Auth::id(), 403, 'Anda tidak diizinkan mengedit artikel ini.');
        
        return view('articles.edit', compact('article'));
    }

    /**
     * Memperbarui artikel yang ada.
     * Metode ini sekarang menangani UPLOAD FILE dan DELETE FILE LAMA.
     * @param Request $request
     * @param Article $article
     * @return RedirectResponse
     */
    public function update(Request $request, Article $article): RedirectResponse
    {
        // OTORISASI: Pastikan editor hanya bisa mengedit artikelnya sendiri
        abort_if($article->user_id !== Auth::id(), 403, 'Anda tidak diizinkan memperbarui artikel ini.');
        
        // 1. Validasi Data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            // Saat update, slug harus unik kecuali jika itu adalah slug artikel ini sendiri
            'slug' => 'nullable|string|unique:articles,slug,' . $article->id, 
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // 2. Pembuatan Slug Otomatis (jika judul berubah)
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        
        // 3. Penanganan Upload File Thumbnail Baru
        if ($request->hasFile('thumbnail_file')) {
            // Hapus file lama jika ada (hanya jika thumbnail_url tidak kosong)
            if ($article->thumbnail_url) {
                // Konversi URL storage ke path yang bisa dihapus (contoh: '/storage/thumbnails/x.jpg' -> 'public/thumbnails/x.jpg')
                $pathToDelete = Str::replaceFirst('/storage/', 'public/', $article->thumbnail_url);
                Storage::delete($pathToDelete);
            }

            // Upload file baru
            $path = $request->file('thumbnail_file')->store('public/thumbnails');
            $validated['thumbnail_url'] = Storage::url($path);
        }

        // 4. Update data
        $article->update($validated);

        return redirect()->route('editor.dashboard')
                         ->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Menghapus artikel.
     * Metode ini sekarang menangani DELETE FILE thumbnail yang terkait.
     * @param Article $article
     * @return RedirectResponse
     */
    public function destroy(Article $article): RedirectResponse
    {
        // OTORISASI: Hanya boleh dihapus oleh penulis atau Admin 
        abort_if($article->user_id !== Auth::id() && Auth::user()->role !== 'admin', 403, 'Akses Ditolak.');
        
        // 1. Hapus file thumbnail dari storage (jika ada)
        if ($article->thumbnail_url) {
            $pathToDelete = Str::replaceFirst('/storage/', 'public/', $article->thumbnail_url);
            Storage::delete($pathToDelete);
        }
        
        // 2. Hapus data artikel
        $article->delete();

        return redirect()->route('editor.dashboard')
                         ->with('success', 'Artikel berhasil dihapus.');
    }
    
    // Metode khusus Admin Review (tidak ada di query Anda, tetapi diasumsikan ada)
    public function reviewIndex(): View
    {
        $articles = Article::where('status', '!=', 'published')
                           ->latest()
                           ->get();
        // Asumsi path admin adalah 'admin.articles.review'
        return view('admin.articles.review', compact('articles'));
    }

    public function updateStatus(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,pending,published,rejected',
        ]);
        $article->update(['status' => $validated['status']]);
        return redirect()->route('admin.review.index')
                         ->with('success', "Status artikel '{$article->title}' berhasil diperbarui menjadi {$validated['status']}.");
    }
}