<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Str; 

/**
 * ArticleController
 * 
 * Controller ini menangani semua operasi CRUD (Create, Read, Update, Delete) 
 * untuk artikel yang dibuat oleh Editor.
 * 
 * Fitur utama:
 * - Dashboard editor untuk melihat artikel milik sendiri
 * - Membuat artikel baru dengan upload thumbnail
 * - Mengedit dan menghapus artikel
 * - Review artikel oleh admin (approve/reject)
 * - Auto-generate slug dari judul artikel
 * 
 * @package App\Http\Controllers
 */
class ArticleController extends Controller
{
    /**
     * Menampilkan daftar semua artikel milik penulis yang sedang login
     * 
     * Method ini berfungsi sebagai Dashboard Editor, menampilkan semua artikel
     * yang dibuat oleh editor yang sedang login, diurutkan dari yang terbaru.
     * 
     * @return View Halaman dashboard editor dengan daftar artikel
     */
    public function index(): View
    {
        // Ambil ID user yang sedang login
        $userId = Auth::id();
        
        // Query artikel yang dimiliki oleh user ini saja
        // Diurutkan dari yang terbaru (latest = order by created_at DESC)
        $articles = Article::where('user_id', $userId)
                           ->latest()
                           ->get();

        // Kirim data artikel ke view editor.dashboard
        return view('editor.dashboard', compact('articles'));
    }

    /**
     * Menampilkan formulir untuk membuat artikel baru
     * 
     * Method ini menampilkan halaman form pembuatan artikel baru
     * yang berisi input untuk judul, konten, kategori, dan thumbnail.
     * 
     * @return View Halaman form pembuatan artikel
     */
    public function create(): View
    {
        return view('editor.create');
    }

    /**
     * Menyimpan artikel baru ke database
     * 
     * Method ini memproses form pembuatan artikel baru, melakukan validasi,
     * upload thumbnail, generate slug otomatis, dan menyimpan ke database.
     * 
     * Proses yang dilakukan:
     * 1. Validasi input dari form
     * 2. Generate slug otomatis dari judul jika tidak diisi manual
     * 3. Upload file thumbnail ke storage/app/public/thumbnails
     * 4. Simpan data artikel dengan status 'draft'
     * 
     * @param Request $request Data dari form pembuatan artikel
     * @return RedirectResponse Redirect ke dashboard editor dengan pesan sukses/error
     */
    public function store(Request $request): RedirectResponse
    {
        // STEP 1: Validasi Data Input
        // Memastikan semua data yang dikirim sesuai dengan aturan
        $validated = $request->validate([
            'title' => 'required|string|max:255',              // Judul wajib diisi, max 255 karakter
            'content' => 'required|string',                     // Konten artikel wajib diisi
            'slug' => 'nullable|string|unique:articles,slug',   // Slug opsional, harus unik jika diisi
            'category_id' => 'required|exists:categories,id',   // Kategori wajib ada di tabel categories
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096', // Thumbnail opsional, max 4MB
        ]);

        // STEP 2: Pembuatan Slug Otomatis
        // Jika slug tidak diisi manual, generate otomatis dari judul
        // Contoh: "Artikel Saya" menjadi "artikel-saya"
        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['slug'] = $slug;

        // STEP 3: Penanganan Upload File Thumbnail
        $thumbnailUrl = null;
        
        // Cek apakah ada file thumbnail yang diupload dan valid
        if ($request->hasFile('thumbnail_file') && $request->file('thumbnail_file')->isValid()) {
            try {
                $file = $request->file('thumbnail_file');
                
                // Generate nama file unik dengan timestamp untuk menghindari duplikasi
                // Format: 1234567890_nama-file.jpg
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                
                // Simpan file ke folder storage/app/public/thumbnails
                $path = $file->storeAs('thumbnails', $filename, 'public');
                
                // Generate URL yang bisa diakses publik
                // Format: /storage/thumbnails/1234567890_nama-file.jpg
                $thumbnailUrl = '/storage/' . $path;
                
                // Log untuk debugging jika diperlukan
                \Log::info('Thumbnail uploaded successfully', [
                    'path' => $path,
                    'url' => $thumbnailUrl,
                    'full_path' => storage_path('app/public/' . $path)
                ]);
            } catch (\Exception $e) {
                // Jika upload gagal, log error dan kembalikan ke form dengan pesan error
                \Log::error('Thumbnail upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return back()->withInput()->with('error', 'Gagal mengupload thumbnail: ' . $e->getMessage());
            }
        }
        
        // STEP 4: Tambahkan data tambahan yang tidak dari form
        $validated['thumbnail_url'] = $thumbnailUrl;  // URL thumbnail yang sudah diupload
        $validated['user_id'] = Auth::id();           // ID user yang sedang login (penulis)
        $validated['status'] = 'draft';               // Status awal artikel adalah draft
        
        // Hapus thumbnail_file dari array karena bukan kolom di database
        unset($validated['thumbnail_file']);
        
        // STEP 5: Simpan artikel ke database
        Article::create($validated);

        // Redirect ke dashboard editor dengan pesan sukses
        return redirect()->route('editor.dashboard')
                          ->with('success', 'Artikel berhasil dibuat dan menunggu tinjauan.');
    }

    /**
     * Menampilkan detail artikel tertentu
     * 
     * Method ini menampilkan halaman detail artikel.
     * Hanya pemilik artikel atau admin yang bisa mengakses.
     * 
     * @param Article $article Model artikel yang akan ditampilkan (Route Model Binding)
     * @return View Halaman detail artikel
     */
    public function show(Article $article): View
    {
        // Otorisasi: Cek apakah user adalah pemilik artikel atau admin
        // Jika bukan admin dan bukan pemilik, tampilkan error 403 Forbidden
        if (Auth::user()->role !== 'admin') {
            abort_if($article->user_id !== Auth::id(), 403); 
        }
        
        return view('articles.show', compact('article'));
    }

    /**
     * Menampilkan formulir untuk mengedit artikel
     * 
     * Method ini menampilkan halaman form edit artikel yang sudah ada.
     * Editor hanya bisa mengedit artikel miliknya sendiri.
     * 
     * @param Article $article Model artikel yang akan diedit (Route Model Binding)
     * @return View Halaman form edit artikel
     */
    public function edit(Article $article): View
    {
        // Otorisasi: Pastikan editor hanya bisa mengedit artikelnya sendiri
        // Jika bukan pemilik, tampilkan error 403 Forbidden
        abort_if($article->user_id !== Auth::id(), 403, 'Anda tidak diizinkan mengedit artikel ini.');
        
        // Kirim data artikel ke view untuk ditampilkan di form
        return view('editor.edit', compact('article')); 
    }

    /**
     * Memperbarui artikel yang sudah ada di database
     * 
     * Method ini memproses form edit artikel, melakukan validasi,
     * upload thumbnail baru (jika ada), dan update data di database.
     * 
     * Proses yang dilakukan:
     * 1. Validasi otorisasi (hanya pemilik yang bisa edit)
     * 2. Validasi input dari form
     * 3. Hapus thumbnail lama dan upload yang baru (jika ada)
     * 4. Update data artikel di database
     * 
     * @param Request $request Data dari form edit artikel
     * @param Article $article Model artikel yang akan diupdate (Route Model Binding)
     * @return RedirectResponse Redirect ke dashboard editor dengan pesan sukses/error
     */
    public function update(Request $request, Article $article): RedirectResponse
    {
        // Otorisasi: Pastikan editor hanya bisa mengedit artikelnya sendiri
        abort_if($article->user_id !== Auth::id(), 403, 'Anda tidak diizinkan memperbarui artikel ini.');
        
        // STEP 1: Validasi Data Input
        // Slug harus unik kecuali untuk artikel ini sendiri (ignore artikel dengan id ini)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'nullable|string|unique:articles,slug,' . $article->id,  // Ignore artikel ini sendiri
            'category_id' => 'required|exists:categories,id',
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);
        
        // STEP 2: Pembuatan Slug Otomatis
        // Generate slug baru dari judul jika slug tidak diisi manual
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        
        // STEP 3: Penanganan Upload File Thumbnail Baru
        if ($request->hasFile('thumbnail_file') && $request->file('thumbnail_file')->isValid()) {
            try {
                // Hapus file thumbnail lama dari storage jika ada
                if ($article->thumbnail_url) {
                    // Konversi URL menjadi path: /storage/thumbnails/file.jpg -> thumbnails/file.jpg
                    $oldPath = Str::replaceFirst('/storage/', '', $article->thumbnail_url);
                    Storage::disk('public')->delete($oldPath);
                    
                    \Log::info('Old thumbnail deleted', ['path' => $oldPath]);
                }

                // Upload file thumbnail baru
                $file = $request->file('thumbnail_file');
                
                // Generate nama file unik
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                
                // Simpan file ke storage/app/public/thumbnails
                $path = $file->storeAs('thumbnails', $filename, 'public');
                
                // Generate URL publik
                $validated['thumbnail_url'] = '/storage/' . $path;
                
                \Log::info('New thumbnail uploaded', [
                    'path' => $path,
                    'url' => $validated['thumbnail_url']
                ]);
            } catch (\Exception $e) {
                // Jika upload gagal, log error dan kembalikan ke form
                \Log::error('Thumbnail update failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return back()->withInput()->with('error', 'Gagal mengupdate thumbnail: ' . $e->getMessage());
            }
        }
        
        // Hapus thumbnail_file dari array karena bukan kolom database
        unset($validated['thumbnail_file']);

        // STEP 4: Update data artikel di database
        $article->update($validated);

        // Redirect ke dashboard dengan pesan sukses
        return redirect()->route('editor.dashboard')
                          ->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Menghapus artikel dari database
     * 
     * Method ini menghapus artikel beserta file thumbnail-nya dari storage.
     * Hanya pemilik artikel atau admin yang bisa menghapus.
     * 
     * Proses yang dilakukan:
     * 1. Validasi otorisasi (pemilik atau admin)
     * 2. Hapus file thumbnail dari storage
     * 3. Hapus data artikel dari database
     * 
     * @param Article $article Model artikel yang akan dihapus (Route Model Binding)
     * @return RedirectResponse Redirect ke dashboard editor dengan pesan sukses
     */
    public function destroy(Article $article): RedirectResponse
    {
        // Otorisasi: Hanya pemilik artikel atau admin yang bisa menghapus
        // Jika bukan keduanya, tampilkan error 403 Forbidden
        abort_if($article->user_id !== Auth::id() && Auth::user()->role !== 'admin', 403, 'Akses Ditolak.');
        
        // STEP 1: Hapus file thumbnail dari storage (jika ada)
        if ($article->thumbnail_url) {
            try {
                // Konversi URL menjadi path: /storage/thumbnails/file.jpg -> thumbnails/file.jpg
                $path = Str::replaceFirst('/storage/', '', $article->thumbnail_url);
                Storage::disk('public')->delete($path);
                
                \Log::info('Thumbnail deleted on article destroy', ['path' => $path]);
            } catch (\Exception $e) {
                // Log error tapi tetap lanjutkan penghapusan artikel
                \Log::error('Failed to delete thumbnail', [
                    'error' => $e->getMessage(),
                    'article_id' => $article->id
                ]);
            }
        }
        
        // STEP 2: Hapus data artikel dari database
        // Ini juga akan menghapus relasi terkait (comments, likes) jika ada cascade delete
        $article->delete();

        // Redirect ke dashboard dengan pesan sukses
        return redirect()->route('editor.dashboard')
                          ->with('success', 'Artikel berhasil dihapus.');
    }

    // ========================================================================
    // METODE KHUSUS UNTUK ADMIN - REVIEW DAN MODERASI ARTIKEL
    // ========================================================================
    
    /**
     * Menampilkan daftar artikel yang perlu direview oleh admin
     * 
     * Method ini menampilkan semua artikel dengan status draft, pending, atau rejected
     * yang perlu ditinjau dan disetujui oleh admin sebelum dipublikasikan.
     * 
     * @return View Halaman review artikel untuk admin
     */
    public function reviewIndex(): View
    {
        // Ambil artikel yang statusnya draft, pending, atau rejected
        // Diurutkan dari yang terbaru
        $articles = Article::whereIn('status', ['draft', 'pending', 'rejected'])
                           ->latest()
                           ->get();
                           
        return view('admin.articles.review', compact('articles'));
    }

    /**
     * Mengupdate status artikel (approve/reject) oleh admin
     * 
     * Method ini memungkinkan admin untuk mengubah status artikel:
     * - draft: Masih dalam tahap penulisan
     * - pending: Menunggu review admin
     * - published: Disetujui dan dipublikasikan
     * - rejected: Ditolak oleh admin
     * 
     * @param Request $request Data status baru dari form
     * @param Article $article Model artikel yang akan diupdate statusnya
     * @return RedirectResponse Redirect ke halaman review dengan pesan sukses
     */
    public function updateStatus(Request $request, Article $article): RedirectResponse
    {
        // Validasi: Status harus salah satu dari 4 pilihan yang valid
        $validated = $request->validate([
            'status' => 'required|in:draft,pending,published,rejected',
        ]);
        
        // Update status artikel
        $article->update(['status' => $validated['status']]);
        
        // Redirect dengan pesan sukses
        return redirect()->route('admin.review.index')
                          ->with('success', "Status artikel '{$article->title}' berhasil diperbarui menjadi {$validated['status']}.");
    }
}
