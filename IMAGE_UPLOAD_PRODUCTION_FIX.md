# Fix: Image Upload Tidak Berfungsi di Production

## 🐛 Masalah

Di production (Zeabur), ketika edit artikel, image/thumbnail tidak bisa diupload dan tidak muncul di dashboard.

## 🔍 Kemungkinan Penyebab

1. **Storage link tidak dibuat** atau tidak berfungsi di production
2. **Permission folder** tidak tepat (tidak writable)
3. **URL path tidak sesuai** dengan APP_URL production
4. **Folder thumbnails tidak ada** di production
5. **Symlink tidak support** di beberapa hosting environment

## ✅ Solusi yang Diterapkan

### 1. Update Upload Logic di Controller

**File**: `app/Http/Controllers/ArticleController.php`

#### Perubahan di Method `store()`:

```php
// SEBELUM: Hardcoded URL path
$thumbnailUrl = '/storage/' . $path;

// SESUDAH: Menggunakan Storage::url() yang lebih reliable
$thumbnailUrl = Storage::disk('public')->url($path);

// TAMBAHAN: Auto-create folder jika belum ada
$thumbnailsPath = storage_path('app/public/thumbnails');
if (!file_exists($thumbnailsPath)) {
    mkdir($thumbnailsPath, 0775, true);
}
```

#### Perubahan di Method `update()`:

```php
// SEBELUM: Simple string replace
$oldPath = Str::replaceFirst('/storage/', '', $article->thumbnail_url);

// SESUDAH: Parse URL untuk support berbagai format
$oldPath = parse_url($article->thumbnail_url, PHP_URL_PATH);
$oldPath = Str::replaceFirst('/storage/', '', $oldPath);

// TAMBAHAN: Cek file exists sebelum delete
if (Storage::disk('public')->exists($oldPath)) {
    Storage::disk('public')->delete($oldPath);
}
```

#### Perubahan di Method `destroy()`:

```php
// SESUDAH: Parse URL dan cek exists
$path = parse_url($article->thumbnail_url, PHP_URL_PATH);
$path = Str::replaceFirst('/storage/', '', $path);

if (Storage::disk('public')->exists($path)) {
    Storage::disk('public')->delete($path);
}
```

**File**: `app/Http/Controllers/AdminController.php`

Update method `destroyArticle()` dengan logic yang sama.

### 2. Keuntungan Perubahan

✅ **Auto-create folder**: Folder thumbnails dibuat otomatis jika belum ada  
✅ **Reliable URL**: Menggunakan `Storage::url()` yang mengikuti konfigurasi `filesystems.php`  
✅ **Support berbagai format URL**: Parse URL dengan `parse_url()` untuk handle full URL atau relative path  
✅ **Safe delete**: Cek file exists sebelum delete untuk menghindari error  
✅ **Better logging**: Log lebih detail untuk debugging di production  

### 3. Script Perbaikan Storage

**File**: `fix-storage-production.sh`

Script bash untuk memperbaiki storage di production:

```bash
#!/bin/bash
# Jalankan di server production

# 1. Create storage link
php artisan storage:link

# 2. Create thumbnails directory
mkdir -p storage/app/public/thumbnails

# 3. Set permissions
chmod -R 775 storage
chmod -R 775 storage/app/public/thumbnails

# 4. Clear caches
php artisan cache:clear
php artisan config:clear
```

**Cara Menggunakan**:

```bash
# Di server production (Zeabur)
chmod +x fix-storage-production.sh
./fix-storage-production.sh
```

## 🚀 Deployment Steps

### Di Production (Zeabur):

1. **Deploy kode terbaru**:
   ```bash
   git push
   ```

2. **SSH ke server** dan jalankan:
   ```bash
   # Create storage link
   php artisan storage:link
   
   # Create thumbnails directory
   mkdir -p storage/app/public/thumbnails
   
   # Set permissions
   chmod -R 775 storage
   chmod -R 775 bootstrap/cache
   
   # Clear caches
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

3. **Verify**:
   ```bash
   # Check storage link
   ls -la public/storage
   
   # Check thumbnails directory
   ls -la storage/app/public/thumbnails
   
   # Test write permission
   touch storage/app/public/thumbnails/test.txt
   rm storage/app/public/thumbnails/test.txt
   ```

## 🧪 Testing

### Test Upload di Production:

1. Login sebagai editor atau admin
2. Buat artikel baru atau edit artikel existing
3. Upload thumbnail image
4. Save artikel
5. Verify:
   - Image muncul di dashboard
   - Image bisa diakses via URL
   - Image muncul di detail artikel

### Check Logs:

```bash
# Di server production
tail -f storage/logs/laravel.log

# Cari log upload:
# - "Thumbnail uploaded successfully"
# - "Thumbnail update failed" (jika error)
```

## 🔧 Troubleshooting

### Problem 1: Storage Link Tidak Berfungsi

**Symptom**: Image upload sukses tapi tidak muncul (404)

**Solution**:
```bash
# Recreate storage link
rm public/storage
php artisan storage:link

# Verify
ls -la public/storage
```

### Problem 2: Permission Denied

**Symptom**: Error "Permission denied" saat upload

**Solution**:
```bash
# Set correct permissions
chmod -R 775 storage
chown -R www-data:www-data storage  # Linux
chown -R _www:_www storage          # macOS

# Verify writable
[ -w storage/app/public/thumbnails ] && echo "Writable" || echo "Not writable"
```

### Problem 3: Folder Tidak Ada

**Symptom**: Error "Directory does not exist"

**Solution**:
```bash
# Create directory
mkdir -p storage/app/public/thumbnails
chmod 775 storage/app/public/thumbnails

# Verify
ls -la storage/app/public/
```

### Problem 4: URL Tidak Sesuai

**Symptom**: Image URL salah (localhost di production)

**Solution**:
```bash
# Check APP_URL di .env
cat .env | grep APP_URL

# Harus sesuai dengan domain production
# Contoh: APP_URL=https://your-app.zeabur.app

# Update jika salah
php artisan config:clear
php artisan config:cache
```

### Problem 5: Disk Space Penuh

**Symptom**: Upload gagal tanpa error jelas

**Solution**:
```bash
# Check disk space
df -h

# Check storage usage
du -sh storage/app/public/thumbnails

# Clean old files if needed
find storage/app/public/thumbnails -mtime +30 -delete
```

## 📊 Monitoring

### Check Upload Statistics:

```bash
# Count thumbnails
find storage/app/public/thumbnails -type f | wc -l

# Total size
du -sh storage/app/public/thumbnails

# Latest uploads
ls -lht storage/app/public/thumbnails | head -10
```

### Check Logs:

```bash
# Upload success logs
grep "Thumbnail uploaded successfully" storage/logs/laravel.log

# Upload error logs
grep "Thumbnail upload failed" storage/logs/laravel.log

# Recent errors
tail -100 storage/logs/laravel.log | grep -i error
```

## 📝 Configuration Check

### Verify filesystems.php:

```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',  // ✓ Correct
    'visibility' => 'public',
],
```

### Verify .env:

```env
FILESYSTEM_DISK=public          # ✓ Correct
APP_URL=https://your-domain.com # ✓ Must match production domain
```

## 🎯 Expected Behavior

### After Fix:

✅ Upload image di create article → Sukses  
✅ Upload image di edit article → Sukses  
✅ Image muncul di dashboard  
✅ Image muncul di detail artikel  
✅ Image bisa diakses via URL  
✅ Old image terhapus saat upload baru  
✅ Image terhapus saat delete artikel  

## 📚 Files Modified

1. `app/Http/Controllers/ArticleController.php` - Update upload logic
2. `app/Http/Controllers/AdminController.php` - Update delete logic
3. `fix-storage-production.sh` - Script perbaikan storage
4. `IMAGE_UPLOAD_PRODUCTION_FIX.md` - Dokumentasi ini

## 🔗 Related Documentation

- `THUMBNAIL_TROUBLESHOOTING.md` - Troubleshooting umum thumbnail
- `THUMBNAIL_UPLOAD_FIXES.md` - History fix upload
- `ZEABUR_DEPLOYMENT.md` - Deployment guide
- `deploy.sh` - Deployment script

---

**Fixed Date**: December 4, 2025  
**Version**: 1.4.2  
**Status**: ✅ Ready for Production
