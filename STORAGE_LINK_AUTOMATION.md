# Storage Link Automation

## 🎯 Tujuan

Memastikan `php artisan storage:link` **SELALU** dijalankan setiap kali:
- Deploy baru
- Restart aplikasi
- Build ulang di production

Ini **CRITICAL** untuk memastikan image upload berfungsi dengan baik.

## 🔧 Implementasi

### 1. Startup Script (`startup.sh`)

Script yang dijalankan setiap kali aplikasi start/restart:

```bash
#!/bin/bash
# Dijalankan oleh Procfile setiap kali app start

# Create storage link
php artisan storage:link --force

# Ensure thumbnails directory exists
mkdir -p storage/app/public/thumbnails
chmod 775 storage/app/public/thumbnails

# Set permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Start web server
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
```

**Kapan dijalankan**: Setiap kali aplikasi start/restart di production

### 2. Procfile

```
web: bash startup.sh
```

**Fungsi**: Menjalankan startup.sh saat aplikasi start

### 3. Deploy Script (`deploy.sh`)

```bash
# Step 5: Create Storage Link
php artisan storage:link --force

# Step 5.1: Ensure thumbnails directory exists
mkdir -p storage/app/public/thumbnails
chmod 775 storage/app/public/thumbnails
```

**Kapan dijalankan**: Saat manual deployment

### 4. Zeabur Configuration (`zeabur.json`)

```json
{
  "buildCommand": "... && php artisan storage:link --force && mkdir -p storage/app/public/thumbnails && ...",
  "startCommand": "bash startup.sh"
}
```

**Kapan dijalankan**: 
- `buildCommand`: Saat build di Zeabur
- `startCommand`: Saat start aplikasi di Zeabur

### 5. Post-Deploy Check (`post-deploy-check.php`)

```php
// Check storage link exists
if (is_link($storageLink) || is_dir($storageLink)) {
    echo "✅ Storage link exists\n";
}

// Check thumbnails directory
if (is_dir($thumbnailsDir) && is_writable($thumbnailsDir)) {
    echo "✅ Thumbnails directory ready\n";
}
```

**Kapan dijalankan**: Setelah deployment untuk verifikasi

## 📋 Checklist Automation

### ✅ Saat Build (Zeabur)
- [x] `zeabur.json` buildCommand menjalankan `storage:link`
- [x] `zeabur.json` buildCommand membuat folder thumbnails
- [x] `zeabur.json` buildCommand set permissions

### ✅ Saat Start/Restart
- [x] `Procfile` menjalankan `startup.sh`
- [x] `startup.sh` menjalankan `storage:link --force`
- [x] `startup.sh` membuat folder thumbnails
- [x] `startup.sh` set permissions
- [x] `startup.sh` verify setup

### ✅ Saat Manual Deploy
- [x] `deploy.sh` menjalankan `storage:link --force`
- [x] `deploy.sh` membuat folder thumbnails
- [x] `deploy.sh` set permissions

### ✅ Verifikasi Post-Deploy
- [x] `post-deploy-check.php` cek storage link
- [x] `post-deploy-check.php` cek thumbnails directory
- [x] `post-deploy-check.php` cek write permissions

## 🔄 Flow Diagram

```
┌─────────────────────────────────────────────────────────┐
│                    DEPLOYMENT FLOW                       │
└─────────────────────────────────────────────────────────┘

1. GIT PUSH
   ↓
2. ZEABUR BUILD (zeabur.json buildCommand)
   ├─ composer install
   ├─ npm install & build
   ├─ php artisan storage:link --force  ← AUTOMATION 1
   ├─ mkdir thumbnails                  ← AUTOMATION 2
   ├─ chmod 775 storage                 ← AUTOMATION 3
   └─ cache configs
   ↓
3. ZEABUR START (zeabur.json startCommand)
   ↓
4. RUN PROCFILE (web: bash startup.sh)
   ↓
5. STARTUP.SH EXECUTION
   ├─ php artisan storage:link --force  ← AUTOMATION 4
   ├─ mkdir thumbnails                  ← AUTOMATION 5
   ├─ chmod 775 storage                 ← AUTOMATION 6
   ├─ verify setup                      ← AUTOMATION 7
   └─ start web server
   ↓
6. APPLICATION RUNNING ✅

┌─────────────────────────────────────────────────────────┐
│                    RESTART FLOW                          │
└─────────────────────────────────────────────────────────┘

1. APP RESTART (manual or auto)
   ↓
2. RUN PROCFILE (web: bash startup.sh)
   ↓
3. STARTUP.SH EXECUTION
   ├─ php artisan storage:link --force  ← AUTOMATION
   ├─ mkdir thumbnails                  ← AUTOMATION
   ├─ chmod 775 storage                 ← AUTOMATION
   └─ verify setup
   ↓
4. APPLICATION RUNNING ✅
```

## 🧪 Testing

### Test 1: Deploy Baru

```bash
# 1. Push ke git
git push

# 2. Wait for Zeabur build & deploy

# 3. SSH ke server dan verify
ls -la public/storage              # Should exist
ls -la storage/app/public/thumbnails  # Should exist
[ -w storage/app/public/thumbnails ] && echo "Writable" || echo "Not writable"

# 4. Test upload image via web interface
```

### Test 2: Restart Aplikasi

```bash
# 1. Restart app di Zeabur dashboard

# 2. Check logs untuk verify startup.sh dijalankan
# Should see:
# 🚀 FAKTAnow Starting Up...
# 🔗 Creating storage link...
# ✓ Storage link OK

# 3. Test upload image via web interface
```

### Test 3: Manual Deploy

```bash
# 1. SSH ke server
ssh user@server

# 2. Run deploy script
bash deploy.sh

# 3. Verify
ls -la public/storage
ls -la storage/app/public/thumbnails

# 4. Test upload
```

## 🚨 Troubleshooting

### Problem: Storage link hilang setelah restart

**Cause**: Procfile tidak menjalankan startup.sh

**Solution**:
```bash
# Verify Procfile
cat Procfile
# Should be: web: bash startup.sh

# If not, update:
echo "web: bash startup.sh" > Procfile
git add Procfile
git commit -m "Fix Procfile"
git push
```

### Problem: Thumbnails directory tidak ada

**Cause**: startup.sh tidak dijalankan atau gagal

**Solution**:
```bash
# Manual fix
mkdir -p storage/app/public/thumbnails
chmod 775 storage/app/public/thumbnails

# Verify startup.sh executable
chmod +x startup.sh

# Check startup.sh logs
# Should see: "📁 Ensuring thumbnails directory exists..."
```

### Problem: Permission denied saat upload

**Cause**: Permissions tidak di-set dengan benar

**Solution**:
```bash
# Manual fix
chmod -R 775 storage
chmod -R 775 storage/app/public/thumbnails

# Verify writable
[ -w storage/app/public/thumbnails ] && echo "OK" || echo "FAIL"
```

## 📊 Monitoring

### Check Storage Link Status

```bash
# Method 1: Direct check
ls -la public/storage

# Method 2: Via PHP
php -r "echo (is_link('public/storage') || is_dir('public/storage')) ? 'OK' : 'FAIL';"

# Method 3: Via artisan
php artisan tinker
>>> file_exists(public_path('storage'))
```

### Check Thumbnails Directory

```bash
# Check exists
ls -la storage/app/public/thumbnails

# Check writable
[ -w storage/app/public/thumbnails ] && echo "Writable" || echo "Not writable"

# Count files
find storage/app/public/thumbnails -type f | wc -l
```

### Check Logs

```bash
# Startup logs (should show storage:link execution)
tail -100 /var/log/app.log | grep "storage"

# Laravel logs (should show upload success)
tail -100 storage/logs/laravel.log | grep "Thumbnail uploaded"
```

## 📝 Files Involved

1. **startup.sh** - Main startup script (runs on every start)
2. **Procfile** - Tells Zeabur to run startup.sh
3. **zeabur.json** - Build and start commands for Zeabur
4. **deploy.sh** - Manual deployment script
5. **post-deploy-check.php** - Post-deployment verification

## ✅ Success Criteria

- ✅ Storage link exists after every deployment
- ✅ Storage link exists after every restart
- ✅ Thumbnails directory exists and writable
- ✅ Image upload works immediately after deployment
- ✅ No manual intervention needed

## 🎯 Benefits

1. **Zero Manual Work**: Storage link dibuat otomatis
2. **Always Available**: Tidak pernah hilang setelah restart
3. **Reliable Uploads**: Image upload selalu berfungsi
4. **Production Ready**: Tidak perlu SSH untuk fix storage
5. **Self-Healing**: Verify dan recreate jika ada masalah

---

**Created**: December 4, 2025  
**Version**: 1.4.2  
**Status**: ✅ Fully Automated
