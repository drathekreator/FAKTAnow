# 🔧 Admin Article Management - Fitur Baru

## ✨ Fitur yang Ditambahkan

### 1. **Admin Bisa Mengubah Status Artikel dari Dashboard**
Admin sekarang bisa mengubah status artikel langsung dari dashboard admin tanpa perlu masuk ke halaman review.

**Status yang tersedia:**
- `DRAFT` - Artikel masih dalam tahap penulisan
- `PENDING` - Menunggu review admin
- `PUBLISHED` - Artikel sudah dipublikasikan dan tampil di homepage
- `REJECTED` - Artikel ditolak oleh admin

**Cara menggunakan:**
1. Login sebagai admin
2. Buka Dashboard Admin
3. Di tabel "Manajemen Artikel", klik dropdown status
4. Pilih status baru
5. Status akan otomatis tersimpan

**Keuntungan:**
- ✅ Lebih cepat mengubah status artikel
- ✅ Tidak perlu masuk ke halaman review terpisah
- ✅ Bisa mengubah status artikel yang sudah published
- ✅ Dropdown dengan warna berbeda untuk setiap status

### 2. **Admin Bisa Mengedit Semua Artikel**
Admin sekarang memiliki akses penuh untuk mengedit semua artikel, termasuk:
- Artikel yang sudah published
- Artikel dari editor manapun
- Artikel dengan status apapun

**Cara menggunakan:**
1. Login sebagai admin
2. Buka Dashboard Admin
3. Di tabel "Manajemen Artikel", klik tombol "Edit" pada artikel yang ingin diedit
4. Edit artikel (judul, konten, kategori, thumbnail, dll)
5. Klik "Update Artikel"
6. Admin akan kembali ke Dashboard Admin dengan pesan sukses

**Keuntungan:**
- ✅ Admin bisa memperbaiki typo atau kesalahan di artikel published
- ✅ Admin bisa mengupdate konten artikel yang sudah lama
- ✅ Admin bisa mengganti thumbnail artikel
- ✅ Admin bisa memindahkan artikel ke kategori lain

## 🔐 Otorisasi & Keamanan

### Editor
- ✅ Hanya bisa edit artikel milik sendiri
- ✅ Tidak bisa edit artikel editor lain
- ✅ Tidak bisa edit artikel yang sudah published oleh admin

### Admin
- ✅ Bisa edit semua artikel dari semua editor
- ✅ Bisa edit artikel dengan status apapun (draft, pending, published, rejected)
- ✅ Bisa mengubah status artikel langsung dari dashboard
- ✅ Setelah edit, kembali ke Dashboard Admin (bukan Dashboard Editor)

## 📝 Perubahan Kode

### 1. ArticleController.php

#### Method `edit()`
```php
/**
 * Menampilkan formulir untuk mengedit artikel
 * 
 * - Editor hanya bisa mengedit artikel miliknya sendiri
 * - Admin bisa mengedit semua artikel (termasuk yang sudah published)
 */
public function edit(Article $article): View
{
    // Otorisasi: Editor hanya bisa edit artikel sendiri, Admin bisa edit semua
    if (Auth::user()->role !== 'admin') {
        abort_if($article->user_id !== Auth::id(), 403, 'Anda tidak diizinkan mengedit artikel ini.');
    }
    
    return view('editor.edit', compact('article')); 
}
```

#### Method `update()`
```php
/**
 * Memperbarui artikel yang sudah ada di database
 * 
 * - Editor hanya bisa update artikel sendiri
 * - Admin bisa update semua artikel
 * - Admin redirect ke dashboard admin setelah update
 * - Editor redirect ke dashboard editor setelah update
 */
public function update(Request $request, Article $article): RedirectResponse
{
    // Otorisasi: Editor hanya bisa edit artikel sendiri, Admin bisa edit semua
    if (Auth::user()->role !== 'admin') {
        abort_if($article->user_id !== Auth::id(), 403, 'Anda tidak diizinkan memperbarui artikel ini.');
    }
    
    // ... validasi dan update ...
    
    // Redirect berdasarkan role
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard')
                          ->with('success', 'Artikel berhasil diperbarui.');
    }
    
    return redirect()->route('editor.dashboard')
                      ->with('success', 'Artikel berhasil diperbarui.');
}
```

### 2. routes/web.php

```php
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // ... route admin lainnya ...
    
    // Admin bisa edit semua artikel (termasuk yang sudah published)
    Route::get('/editor/articles/{article}/edit', [ArticleController::class, 'edit'])->name('admin.articles.edit');
    Route::put('/editor/articles/{article}', [ArticleController::class, 'update'])->name('admin.articles.update');
});
```

### 3. admin/dashboard.blade.php

#### Dropdown Status Artikel
```html
<td class="px-6 py-4">
    <form action="{{ route('admin.articles.updateStatus', $article->slug) }}" method="POST">
        @csrf
        @method('PUT')
        <select name="status" onchange="this.form.submit()" 
            class="px-3 py-1.5 text-xs font-bold rounded-full border-0 cursor-pointer transition
            {{ $article->status == 'published' ? 'bg-green-100 text-green-700' : 
               ($article->status == 'draft' ? 'bg-gray-100 text-gray-700' : 
               ($article->status == 'pending' ? 'bg-yellow-100 text-yellow-700' :
               'bg-red-100 text-red-700')) }}">
            <option value="draft" {{ $article->status == 'draft' ? 'selected' : '' }}>DRAFT</option>
            <option value="pending" {{ $article->status == 'pending' ? 'selected' : '' }}>PENDING</option>
            <option value="published" {{ $article->status == 'published' ? 'selected' : '' }}>PUBLISHED</option>
            <option value="rejected" {{ $article->status == 'rejected' ? 'selected' : '' }}>REJECTED</option>
        </select>
    </form>
</td>
```

## 🧪 Testing

### Test 1: Admin Mengubah Status Artikel
1. Login sebagai admin (admin@portalberita.com / password)
2. Buka Dashboard Admin
3. Cari artikel dengan status "PUBLISHED"
4. Klik dropdown status, ubah ke "DRAFT"
5. ✅ Status berubah otomatis
6. ✅ Artikel tidak lagi tampil di homepage
7. Ubah kembali ke "PUBLISHED"
8. ✅ Artikel kembali tampil di homepage

### Test 2: Admin Mengedit Artikel Published
1. Login sebagai admin
2. Buka Dashboard Admin
3. Cari artikel dengan status "PUBLISHED"
4. Klik tombol "Edit"
5. ✅ Form edit terbuka dengan data artikel
6. Ubah judul artikel
7. Klik "Update Artikel"
8. ✅ Artikel berhasil diupdate
9. ✅ Redirect ke Dashboard Admin
10. ✅ Perubahan tersimpan

### Test 3: Editor Tidak Bisa Edit Artikel Lain
1. Login sebagai editor (editor@portalberita.com / password)
2. Coba akses URL edit artikel milik editor lain
3. ✅ Error 403 Forbidden
4. ✅ Pesan: "Anda tidak diizinkan mengedit artikel ini."

### Test 4: Admin Edit Artikel dari Editor Lain
1. Login sebagai admin
2. Buka Dashboard Admin
3. Cari artikel dari editor lain
4. Klik tombol "Edit"
5. ✅ Form edit terbuka
6. Edit artikel
7. ✅ Berhasil diupdate
8. ✅ Redirect ke Dashboard Admin

## 📊 Use Cases

### Use Case 1: Memperbaiki Typo di Artikel Published
**Scenario:** Ada typo di artikel yang sudah published dan banyak dibaca

**Solution:**
1. Admin login
2. Buka Dashboard Admin
3. Cari artikel yang ada typo
4. Klik "Edit"
5. Perbaiki typo
6. Klik "Update Artikel"
7. ✅ Artikel langsung terupdate tanpa perlu unpublish

### Use Case 2: Mengubah Artikel Published ke Draft
**Scenario:** Artikel published ternyata ada informasi yang salah dan perlu direview ulang

**Solution:**
1. Admin login
2. Buka Dashboard Admin
3. Cari artikel yang bermasalah
4. Klik dropdown status
5. Ubah dari "PUBLISHED" ke "DRAFT"
6. ✅ Artikel langsung hilang dari homepage
7. Admin bisa edit artikel dengan tenang
8. Setelah selesai, ubah status kembali ke "PUBLISHED"

### Use Case 3: Memindahkan Artikel ke Kategori Lain
**Scenario:** Artikel published salah kategori

**Solution:**
1. Admin login
2. Buka Dashboard Admin
3. Cari artikel yang salah kategori
4. Klik "Edit"
5. Ubah kategori di dropdown
6. Klik "Update Artikel"
7. ✅ Artikel pindah kategori tanpa perlu unpublish

## 🎨 UI/UX Improvements

### Dropdown Status dengan Warna
- 🟢 **PUBLISHED** - Hijau (artikel live di homepage)
- ⚪ **DRAFT** - Abu-abu (masih dalam penulisan)
- 🟡 **PENDING** - Kuning (menunggu review)
- 🔴 **REJECTED** - Merah (ditolak admin)

### Auto-submit Form
- Dropdown status langsung submit saat diubah
- Tidak perlu klik tombol "Save" atau "Update"
- User experience lebih smooth

### Tombol Edit di Dashboard
- Tombol "Edit" berwarna biru
- Tombol "Hapus" berwarna merah
- Jelas dan mudah dibedakan

## 🔄 Workflow

### Workflow Editor
```
Editor Login
    ↓
Dashboard Editor
    ↓
Buat/Edit Artikel Sendiri
    ↓
Submit (Status: Draft/Pending)
    ↓
Menunggu Review Admin
```

### Workflow Admin
```
Admin Login
    ↓
Dashboard Admin
    ↓
Lihat Semua Artikel
    ↓
Opsi 1: Ubah Status (Draft/Pending/Published/Rejected)
Opsi 2: Edit Artikel (Semua artikel, termasuk published)
Opsi 3: Hapus Artikel
    ↓
Artikel Terupdate
```

## 📈 Benefits

### Untuk Admin
- ✅ Kontrol penuh atas semua artikel
- ✅ Bisa memperbaiki kesalahan dengan cepat
- ✅ Tidak perlu unpublish artikel untuk edit
- ✅ Bisa mengubah status artikel dengan mudah

### Untuk Editor
- ✅ Artikel mereka bisa diperbaiki oleh admin jika ada kesalahan
- ✅ Tidak perlu khawatir typo di artikel published
- ✅ Admin bisa membantu improve kualitas artikel

### Untuk User/Pembaca
- ✅ Artikel selalu up-to-date
- ✅ Typo dan kesalahan cepat diperbaiki
- ✅ Konten berkualitas tinggi

## 🚀 Future Enhancements

Fitur yang bisa ditambahkan di masa depan:
- [ ] History/log perubahan artikel (siapa edit, kapan, apa yang diubah)
- [ ] Bulk action (ubah status banyak artikel sekaligus)
- [ ] Filter artikel berdasarkan status di dashboard
- [ ] Notifikasi ke editor saat artikelnya diedit oleh admin
- [ ] Preview artikel sebelum publish
- [ ] Schedule publish (publish artikel di waktu tertentu)

---

**Status:** ✅ IMPLEMENTED & TESTED
**Version:** 1.3.0
**Last Updated:** December 2024
