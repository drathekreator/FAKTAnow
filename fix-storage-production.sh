#!/bin/bash

# Script untuk memperbaiki masalah storage di production
# Jalankan script ini di server production (Zeabur)

echo "🔧 FAKTAnow - Fix Storage Production"
echo "===================================="
echo ""

# Step 1: Cek apakah storage link ada
echo "1️⃣  Checking storage link..."
if [ -L "public/storage" ]; then
    echo "   ✓ Storage link exists"
else
    echo "   ✗ Storage link NOT found"
    echo "   Creating storage link..."
    php artisan storage:link
    echo "   ✓ Storage link created"
fi
echo ""

# Step 2: Cek dan buat folder thumbnails
echo "2️⃣  Checking thumbnails directory..."
if [ -d "storage/app/public/thumbnails" ]; then
    echo "   ✓ Thumbnails directory exists"
else
    echo "   ✗ Thumbnails directory NOT found"
    echo "   Creating thumbnails directory..."
    mkdir -p storage/app/public/thumbnails
    echo "   ✓ Thumbnails directory created"
fi
echo ""

# Step 3: Set permissions
echo "3️⃣  Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 775 storage/app/public/thumbnails
echo "   ✓ Permissions set (775)"
echo ""

# Step 4: Verify writable
echo "4️⃣  Verifying write permissions..."
if [ -w "storage/app/public/thumbnails" ]; then
    echo "   ✓ Thumbnails directory is WRITABLE"
else
    echo "   ✗ Thumbnails directory is NOT writable"
    echo "   Trying to fix..."
    chmod -R 777 storage/app/public/thumbnails
    if [ -w "storage/app/public/thumbnails" ]; then
        echo "   ✓ Fixed! Directory is now writable"
    else
        echo "   ✗ Still not writable. Check server permissions."
    fi
fi
echo ""

# Step 5: Test file creation
echo "5️⃣  Testing file creation..."
TEST_FILE="storage/app/public/thumbnails/test_$(date +%s).txt"
if echo "test" > "$TEST_FILE" 2>/dev/null; then
    echo "   ✓ File creation SUCCESS"
    rm "$TEST_FILE"
    echo "   ✓ Test file cleaned up"
else
    echo "   ✗ File creation FAILED"
    echo "   Check server permissions and disk space"
fi
echo ""

# Step 6: Clear caches
echo "6️⃣  Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
echo "   ✓ Caches cleared"
echo ""

# Step 7: Show storage info
echo "7️⃣  Storage Information:"
echo "   Storage path: $(pwd)/storage/app/public"
echo "   Public link: $(pwd)/public/storage"
echo "   Thumbnails: $(pwd)/storage/app/public/thumbnails"
echo ""

# Step 8: List existing thumbnails
echo "8️⃣  Existing thumbnails:"
THUMB_COUNT=$(find storage/app/public/thumbnails -type f 2>/dev/null | wc -l)
echo "   Found $THUMB_COUNT thumbnail(s)"
if [ $THUMB_COUNT -gt 0 ]; then
    echo "   Latest 5 files:"
    ls -lht storage/app/public/thumbnails | head -6
fi
echo ""

echo "✅ Storage fix completed!"
echo ""
echo "📝 Next steps:"
echo "   1. Try uploading a new image"
echo "   2. Check Laravel logs: storage/logs/laravel.log"
echo "   3. If still failing, check APP_URL in .env matches your domain"
echo ""
