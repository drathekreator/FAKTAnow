# Fix: Admin dan Editor Kehilangan Akses Edit Artikel Setelah Update Status

## 🐛 Masalah

Setelah admin mengubah status artikel (draft/published), baik admin maupun editor kehilangan akses untuk mengedit artikel tersebut. Halaman edit tidak bisa diakses dan menampilkan error "Akses Ditolak".

## 🔍 Penyebab

Route edit artikel didefinisikan di 2 tempat yang berbeda dengan middleware yang berbeda:

1. **Route Admin** (hanya admin): 
   ```php
   Route::middleware('role:admin')->group(function () {
       Route::get('/editor/articles/{article}/edit', ...);
   });
   ```

2. **Route Editor** (hanya editor):
   ```php
   Route::middleware('role:editor')->group(function () {
       Route::resource('editor/articles', ...);
   });
   ```

Masalahnya adalah middleware `role:admin` dan `role:editor` bersifat **eksklusif** - hanya satu role yang bisa mengakses. Ketika admin mengubah status artikel, route yang aktif adalah route admin, sehingga editor tidak bisa akses. Begitu juga sebaliknya.

## ✅ Solusi

### 1. Update Middleware `CheckUserRole` untuk Support Multiple Roles

**File**: `app/Http/Middleware/CheckUserRole.php`

Mengubah middleware agar bisa menerima multiple roles dengan pemisah koma:

```php
// SEBELUM: Hanya menerima 1 role
public function handle(Request $request, Closure $next, string $role): Response
{
    if (Auth::user()->role === $role) {
        return $next($request);
    }
    // ...
}

// SESUDAH: Bisa menerima multiple roles
public function handle(Request $request, Closure $next, string $roles): Response
{
    $allowedRoles = explode(',', $roles);
    $userRole = Auth::user()->role;
    
    if (in_array($userRole, $allowedRoles)) {
        return $next($request);
    }
    // ...
}
```

**Cara Kerja**:
- Input: `'admin,editor'`
- Diubah menjadi array: `['admin', 'editor']`
- Cek apakah role user ada di array tersebut
- Jika ya, izinkan akses

### 2. Restructure Routes untuk Edit Artikel

**File**: `routes/web.php`

Memisahkan route edit artikel ke grup tersendiri yang bisa diakses oleh **admin DAN editor**:

```php
// ROUTE KHUSUS ADMIN (tanpa edit artikel)
Route::middleware('role:admin')->group(function () {
    Route::get('/admin/dashboard', ...);
    Route::put('/admin/articles/{article}/status', ...);
    // ... routes admin lainnya
});

// ROUTE KHUSUS EDITOR (tanpa edit artikel)
Route::middleware('role:editor')->group(function () {
    Route::get('/editor/dashboard', ...);
    Route::resource('editor/articles', ...)
        ->only(['create', 'store', 'destroy']); // Hanya create, store, destroy
});

// ROUTE EDIT ARTIKEL - Bisa diakses ADMIN dan EDITOR
Route::middleware(['auth', 'verified', 'role:admin,editor'])->group(function () {
    Route::get('/editor/articles/{article}/edit', ...)->name('articles.edit');
    Route::put('/editor/articles/{article}', ...)->name('articles.update');
});
```

**Keuntungan**:
- ✅ Admin bisa edit semua artikel (authorization di controller)
- ✅ Editor bisa edit artikel miliknya sendiri (authorization di controller)
- ✅ Tidak ada konflik route
- ✅ Akses tidak hilang setelah update status

### 3. Authorization Logic di Controller Tetap Sama

**File**: `app/Http/Controllers/ArticleController.php`

Authorization logic di controller tidak berubah dan tetap berfungsi:

```php
public function edit(Article $article): View
{
    // Editor hanya bisa edit artikel sendiri, Admin bisa edit semua
    if (Auth::user()->role !== 'admin') {
        abort_if($article->user_id !== Auth::id(), 403);
    }
    return view('editor.edit', compact('article'));
}

public function update(Request $request, Article $article): RedirectResponse
{
    // Editor hanya bisa edit artikel sendiri, Admin bisa edit semua
    if (Auth::user()->role !== 'admin') {
        abort_if($article->user_id !== Auth::id(), 403);
    }
    // ... update logic
}
```

## 🎯 Hasil

### Sebelum Fix:
- ❌ Admin ubah status → Editor tidak bisa edit
- ❌ Editor edit artikel → Admin tidak bisa edit
- ❌ Akses hilang setelah update status

### Setelah Fix:
- ✅ Admin bisa edit semua artikel (draft/published)
- ✅ Editor bisa edit artikel miliknya sendiri (draft/published)
- ✅ Akses tetap ada setelah update status
- ✅ Authorization logic di controller tetap berfungsi

## 📝 Testing Checklist

- [x] Admin bisa edit artikel draft
- [x] Admin bisa edit artikel published
- [x] Admin bisa ubah status artikel
- [x] Editor bisa edit artikel draft miliknya
- [x] Editor bisa edit artikel published miliknya
- [x] Editor tidak bisa edit artikel orang lain
- [x] Akses edit tidak hilang setelah update status
- [x] Redirect sesuai role (admin → admin dashboard, editor → editor dashboard)

## 🔧 Files Modified

1. `app/Http/Middleware/CheckUserRole.php` - Support multiple roles
2. `routes/web.php` - Restructure routes untuk edit artikel

## 📚 Dokumentasi Terkait

- `ADMIN_ARTICLE_MANAGEMENT.md` - Fitur manajemen artikel admin
- `FEATURES.md` - Daftar fitur lengkap aplikasi

---

**Fixed Date**: December 4, 2025  
**Version**: 1.4.1
