# Thumbnail Upload Troubleshooting Guide

## Masalah Umum dan Solusi

### 1. Thumbnail Tidak Tersimpan di Storage

**Gejala**: File tidak muncul di `storage/app/public/thumbnails/`

**Penyebab**:
- Storage link belum dibuat
- Permission folder salah
- Disk storage tidak dikonfigurasi dengan benar

**Solusi**:

```bash
# 1. Buat storage link
php artisan storage:link

# 2. Cek apakah link berhasil dibuat
# Windows:
dir public\storage

# Linux/Mac:
ls -la public/storage

# 3. Set permission yang benar (Linux/Mac)
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 4. Pastikan folder thumbnails ada
mkdir -p storage/app/public/thumbnails
```

### 2. Thumbnail Tidak Tampil di Browser

**Gejala**: Image broken atau 404 Not Found

**Penyebab**:
- URL thumbnail salah
- Storage link tidak ada
- File tidak ada di storage

**Solusi**:

1. **Cek URL di database**:
   ```bash
   php artisan tinker
   >>> App\Models\Article::first()->thumbnail_url
   ```
   
   URL yang benar: `/storage/thumbnails/filename.jpg`

2. **Cek file fisik**:
   ```bash
   # Windows
   dir storage\app\public\thumbnails
   
   # Linux/Mac
   ls -la storage/app/public/thumbnails/
   ```

3. **Cek storage link**:
   ```bash
   # Windows
   dir public\storage
   
   # Linux/Mac
   ls -la public/storage
   ```

4. **Recreate storage link jika perlu**:
   ```bash
   # Hapus link lama (jika ada)
   # Windows
   rmdir public\storage
   
   # Linux/Mac
   rm public/storage
   
   # Buat link baru
   php artisan storage:link
   ```

### 3. Error "Failed to open stream"

**Gejala**: Error saat upload file

**Penyebab**:
- Permission folder salah
- Disk space penuh
- PHP upload settings terlalu kecil

**Solusi**:

1. **Cek PHP upload settings** di `php.ini`:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   max_file_uploads = 20
   ```

2. **Restart web server** setelah edit php.ini

3. **Cek disk space**:
   ```bash
   # Windows
   wmic logicaldisk get size,freespace,caption
   
   # Linux/Mac
   df -h
   ```

### 4. Thumbnail Lama Tidak Terhapus

**Gejala**: File lama masih ada di storage setelah update

**Penyebab**:
- Path delete salah
- Permission tidak cukup

**Solusi**:

Kode sudah diperbaiki di `ArticleController.php`:

```php
// Hapus file lama
if ($article->thumbnail_url) {
    $path = Str::replaceFirst('/storage/', '', $article->thumbnail_url);
    Storage::disk('public')->delete($path);
}
```

### 5. Validasi File Gagal

**Gejala**: Error "The thumbnail file must be an image"

**Penyebab**:
- File bukan image
- Extension tidak didukung
- File corrupt

**Solusi**:

File types yang didukung:
- JPEG (.jpg, .jpeg)
- PNG (.png)
- GIF (.gif)
- WebP (.webp)

Max size: 4MB (4096 KB)

### 6. Thumbnail Tidak Muncul di Production

**Gejala**: Works di local, tidak di production

**Penyebab**:
- Storage link tidak dibuat di production
- Permission salah di production
- APP_URL tidak sesuai

**Solusi**:

1. **Set APP_URL di `.env`**:
   ```env
   APP_URL=https://yourdomain.com
   ```

2. **Buat storage link di production**:
   ```bash
   php artisan storage:link
   ```

3. **Set permission**:
   ```bash
   chmod -R 775 storage
   chmod -R 775 bootstrap/cache
   chown -R www-data:www-data storage
   ```

4. **Clear cache**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

## Testing Upload

### Manual Test

1. **Login sebagai editor**
2. **Buat artikel baru**
3. **Upload thumbnail**
4. **Submit form**
5. **Cek di database**:
   ```bash
   php artisan tinker
   >>> $article = App\Models\Article::latest()->first();
   >>> echo $article->thumbnail_url;
   ```

6. **Cek file fisik**:
   ```bash
   # Windows
   dir storage\app\public\thumbnails
   
   # Linux/Mac
   ls -la storage/app/public/thumbnails/
   ```

7. **Akses via browser**:
   ```
   http://localhost:8000/storage/thumbnails/filename.jpg
   ```

### Automated Test Script

Buat file `test_upload.php`:

```php
<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Storage;

echo "=== Testing Storage Configuration ===\n\n";

// Test 1: Check storage link
echo "1. Storage Link: ";
echo file_exists(public_path('storage')) ? "✓ EXISTS\n" : "✗ NOT FOUND\n";

// Test 2: Check thumbnails directory
echo "2. Thumbnails Directory: ";
$thumbDir = storage_path('app/public/thumbnails');
echo is_dir($thumbDir) ? "✓ EXISTS\n" : "✗ NOT FOUND\n";

// Test 3: Check write permission
echo "3. Write Permission: ";
echo is_writable(storage_path('app/public')) ? "✓ WRITABLE\n" : "✗ NOT WRITABLE\n";

// Test 4: Test file write
echo "4. Test File Write: ";
try {
    $testFile = 'test_' . time() . '.txt';
    Storage::disk('public')->put('thumbnails/' . $testFile, 'test content');
    echo "✓ SUCCESS\n";
    
    // Clean up
    Storage::disk('public')->delete('thumbnails/' . $testFile);
} catch (\Exception $e) {
    echo "✗ FAILED: " . $e->getMessage() . "\n";
}

// Test 5: List existing thumbnails
echo "\n5. Existing Thumbnails:\n";
$files = Storage::disk('public')->files('thumbnails');
if (empty($files)) {
    echo "   No thumbnails found\n";
} else {
    foreach ($files as $file) {
        echo "   - " . $file . "\n";
    }
}

echo "\n=== Test Complete ===\n";
```

Run test:
```bash
php test_upload.php
```

## Debugging Tips

### Enable Logging

Tambahkan di `config/logging.php`:

```php
'channels' => [
    'storage' => [
        'driver' => 'single',
        'path' => storage_path('logs/storage.log'),
        'level' => 'debug',
    ],
],
```

### Check Logs

```bash
# Laravel log
tail -f storage/logs/laravel.log

# Storage log (jika sudah dikonfigurasi)
tail -f storage/logs/storage.log

# Web server log
# Apache
tail -f /var/log/apache2/error.log

# Nginx
tail -f /var/log/nginx/error.log
```

### Common Log Messages

**Success**:
```
Thumbnail uploaded successfully
path: thumbnails/1234567890_article-title.jpg
url: /storage/thumbnails/1234567890_article-title.jpg
```

**Failure**:
```
Thumbnail upload failed
error: The file "..." was not uploaded due to an unknown error.
```

## Quick Fix Checklist

- [ ] Storage link created (`php artisan storage:link`)
- [ ] Thumbnails directory exists (`storage/app/public/thumbnails`)
- [ ] Correct permissions (775 for storage)
- [ ] PHP upload settings adequate (10MB+)
- [ ] APP_URL configured correctly in `.env`
- [ ] Config cache cleared (`php artisan config:clear`)
- [ ] File validation rules correct (max:4096)
- [ ] Using correct disk ('public')
- [ ] URL format correct (`/storage/thumbnails/...`)

---

**Last Updated**: December 3, 2025  
**Version**: 1.1.1
