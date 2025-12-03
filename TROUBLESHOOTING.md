# Troubleshooting Guide - FAKTAnow

## Masalah Umum dan Solusinya

### 1. Error "Class 'App\Http\Middleware\CheckUserRole' not found"

**Penyebab**: Middleware belum terdaftar di bootstrap/app.php

**Solusi**:
```bash
# Pastikan file bootstrap/app.php sudah diupdate
# Atau jalankan:
php artisan optimize:clear
```

### 2. Error "SQLSTATE[HY000]: General error: 1 no such table: categories"

**Penyebab**: Migrasi belum dijalankan atau tabel belum dibuat

**Solusi**:
```bash
php artisan migrate
php artisan db:seed --class=CategorySeeder
```

### 3. Error "The storage link could not be created"

**Penyebab**: Symbolic link untuk storage belum dibuat

**Solusi**:
```bash
php artisan storage:link
```

### 4. Gambar thumbnail tidak muncul

**Penyebab**: 
- Storage link belum dibuat
- Path thumbnail salah
- File tidak ada

**Solusi**:
```bash
# Buat storage link
php artisan storage:link

# Pastikan folder storage/app/public/thumbnails ada
mkdir -p storage/app/public/thumbnails

# Set permission (Linux/Mac)
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 5. Error "419 Page Expired" saat submit form

**Penyebab**: CSRF token expired atau tidak ada

**Solusi**:
- Pastikan setiap form memiliki `@csrf`
- Clear cache: `php artisan cache:clear`
- Refresh halaman dan coba lagi

### 6. Error "Route [login] not defined"

**Penyebab**: Route auth belum terdaftar

**Solusi**:
```bash
# Pastikan file routes/auth.php sudah ada
# Dan sudah di-include di routes/web.php
php artisan route:list | grep login
```

### 7. Tidak bisa login sebagai admin

**Penyebab**: Role user belum diset ke 'admin'

**Solusi**:
```sql
-- Buka database dan jalankan:
UPDATE users SET role = 'admin' WHERE email = 'your-email@example.com';
```

Atau via tinker:
```bash
php artisan tinker
>>> $user = User::where('email', 'your-email@example.com')->first();
>>> $user->role = 'admin';
>>> $user->save();
```

### 8. Error "Undefined variable $categories"

**Penyebab**: Controller tidak mengirim variabel categories ke view

**Solusi**: Sudah diperbaiki di HomePageController. Pastikan menggunakan versi terbaru.

### 9. Artikel tidak muncul di homepage

**Penyebab**: 
- Tidak ada artikel dengan status 'published'
- Database kosong

**Solusi**:
```bash
# Buat artikel dummy via tinker
php artisan tinker
>>> $user = User::first();
>>> $category = Category::first();
>>> Article::create([
    'user_id' => $user->id,
    'category_id' => $category->id,
    'title' => 'Artikel Test',
    'slug' => 'artikel-test',
    'content' => 'Ini adalah artikel test',
    'status' => 'published'
]);
```

### 10. Error "Class 'Str' not found"

**Penyebab**: Namespace Str tidak di-import

**Solusi**: Sudah diperbaiki. Pastikan menggunakan `Illuminate\Support\Str` atau helper `Str::` di Blade.

### 11. CSS/JS tidak load

**Penyebab**: 
- Vite belum di-build
- Asset belum di-compile

**Solusi**:
```bash
# Development
npm run dev

# Production
npm run build
```

### 12. Error "Permission denied" saat upload file

**Penyebab**: Permission folder storage salah

**Solusi** (Linux/Mac):
```bash
sudo chown -R www-data:www-data storage
sudo chmod -R 775 storage
```

Windows: Pastikan folder storage tidak read-only

### 13. Komentar tidak muncul

**Penyebab**: Komentar belum di-approve oleh admin

**Solusi**:
- Login sebagai admin
- Buka `/admin/comments/moderate`
- Approve komentar yang ingin ditampilkan

### 14. Error "Too few arguments to function"

**Penyebab**: Parameter function tidak lengkap

**Solusi**: Pastikan semua controller sudah menggunakan versi terbaru dari repository.

### 15. Pagination tidak bekerja

**Penyebab**: Tailwind CSS pagination view belum di-publish

**Solusi**:
```bash
php artisan vendor:publish --tag=laravel-pagination
```

## Command Berguna untuk Debugging

```bash
# Clear semua cache
php artisan optimize:clear

# Lihat semua route
php artisan route:list

# Lihat log error
tail -f storage/logs/laravel.log

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Recreate database (HATI-HATI: Menghapus semua data)
php artisan migrate:fresh --seed
```

## Kontak Support

Jika masalah masih berlanjut, silakan:
1. Cek file log di `storage/logs/laravel.log`
2. Buat issue di repository GitHub
3. Hubungi tim pengembang

---

**Tips**: Selalu backup database sebelum melakukan perubahan besar!
