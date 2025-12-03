<?php

namespace App\Http\Controllers;

use App\Models\Article; // Ganti dengan Model Article Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Str; 

class ArticleController extends Controller
{
    /**
     * Menampilkan daftar semua artikel milik penulis yang sedang login.
     * Ini juga berfungsi sebagai Dashboard Editor.
     * @return View
     */
    public function index(): View
    {
        $userId = Auth::id();
        $articles = Article::where('user_id', $userId)
                           ->latest()
                           ->get();

        // Mengarahkan ke view editor.dashboard
        return view('editor.dashboard', compact('articles'));
    }

    /**
     * Menampilkan formulir untuk membuat artikel baru.
     * @return View
     */
    public function create(): View
    {
        // Mengarahkan ke view editor.create
        return view('editor.create');
    }

    /**
     * Menyimpan artikel baru yang dibuat.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'nullable|string|unique:articles,slug',
            'category_id' => 'required|exists:categories,id',
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096', 
        ]);

        // 2. Pembuatan Slug Otomatis
        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['slug'] = $slug;

        // 3. Penanganan Upload File Thumbnail
        $thumbnailUrl = null;
        if ($request->hasFile('thumbnail_file') && $request->file('thumbnail_file')->isValid()) {
            try {
                $file = $request->file('thumbnail_file');
                
                // Generate unique filename
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                
                // Store file di public disk
                $path = $file->storeAs('thumbnails', $filename, 'public');
                
                // Generate URL
                $thumbnailUrl = '/storage/' . $path;
                
                \Log::info('Thumbnail uploaded successfully', [
                    'path' => $path,
                    'url' => $thumbnailUrl,
                    'full_path' => storage_path('app/public/' . $path)
                ]);
            } catch (\Exception $e) {
                \Log::error('Thumbnail upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return back()->withInput()->with('error', 'Gagal mengupload thumbnail: ' . $e->getMessage());
            }
        }
        
        // 4. Tambahkan data ke validated
        $validated['thumbnail_url'] = $thumbnailUrl;
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'draft'; 
        
        // Remove thumbnail_file dari validated karena bukan kolom database
        unset($validated['thumbnail_file']);
        
        // 5. Simpan data
        Article::create($validated);

        return redirect()->route('editor.dashboard')
                          ->with('success', 'Artikel berhasil dibuat dan menunggu tinjauan.');
    }

    /**
     * Menampilkan artikel tertentu.
     * @param Article $article
     * @return View
     */
    public function show(Article $article): View
    {
        // Otorisasi: hanya pemilik yang boleh melihat (atau Admin)
        if (Auth::user()->role !== 'admin') {
            abort_if($article->user_id !== Auth::id(), 403); 
        }
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
        
        // PASTIKAN INI SESUAI DENGAN LOKASI FILE VIEW ANDA
        return view('editor.edit', compact('article')); 
    }

    /**
     * Memperbarui artikel yang ada.
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
            'slug' => 'nullable|string|unique:articles,slug,' . $article->id,
            'category_id' => 'required|exists:categories,id',
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);
        
        // 2. Pembuatan Slug Otomatis (jika judul berubah dan slug kosong)
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        
        // 3. Penanganan Upload File Thumbnail Baru
        if ($request->hasFile('thumbnail_file') && $request->file('thumbnail_file')->isValid()) {
            try {
                // Hapus file lama jika ada 
                if ($article->thumbnail_url) {
                    $oldPath = Str::replaceFirst('/storage/', '', $article->thumbnail_url);
                    Storage::disk('public')->delete($oldPath);
                    
                    \Log::info('Old thumbnail deleted', ['path' => $oldPath]);
                }

                // Upload file baru
                $file = $request->file('thumbnail_file');
                
                // Generate unique filename
                $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                
                // Store file di public disk
                $path = $file->storeAs('thumbnails', $filename, 'public');
                
                // Generate URL
                $validated['thumbnail_url'] = '/storage/' . $path;
                
                \Log::info('New thumbnail uploaded', [
                    'path' => $path,
                    'url' => $validated['thumbnail_url']
                ]);
            } catch (\Exception $e) {
                \Log::error('Thumbnail update failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return back()->withInput()->with('error', 'Gagal mengupdate thumbnail: ' . $e->getMessage());
            }
        }
        
        // Remove thumbnail_file dari validated karena bukan kolom database
        unset($validated['thumbnail_file']);

        // 4. Update data
        $article->update($validated);

        return redirect()->route('editor.dashboard')
                          ->with('success', 'Artikel berhasil diperbarui.');
    }

    /**
     * Menghapus artikel.
     * @param Article $article
     * @return RedirectResponse
     */
    public function destroy(Article $article): RedirectResponse
    {
        // OTORISASI: Hanya boleh dihapus oleh penulis atau Admin 
        abort_if($article->user_id !== Auth::id() && Auth::user()->role !== 'admin', 403, 'Akses Ditolak.');
        
        // 1. Hapus file thumbnail dari storage (jika ada)
        if ($article->thumbnail_url) {
            try {
                $path = Str::replaceFirst('/storage/', '', $article->thumbnail_url);
                Storage::disk('public')->delete($path);
                
                \Log::info('Thumbnail deleted on article destroy', ['path' => $path]);
            } catch (\Exception $e) {
                \Log::error('Failed to delete thumbnail', [
                    'error' => $e->getMessage(),
                    'article_id' => $article->id
                ]);
            }
        }
        
        // 2. Hapus data artikel
        $article->delete();

        return redirect()->route('editor.dashboard')
                          ->with('success', 'Artikel berhasil dihapus.');
    }
    
    // Metode khusus Admin Review
    public function reviewIndex(): View
    {
        $articles = Article::whereIn('status', ['draft', 'pending', 'rejected'])
                           ->latest()
                           ->get();
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