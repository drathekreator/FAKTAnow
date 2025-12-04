#!/bin/bash

# FAKTAnow Startup Script
# Script ini dijalankan setiap kali aplikasi start/restart di production
# Memastikan storage link dan permissions selalu benar

echo "🚀 FAKTAnow Starting Up..."

# Step 1: Create Storage Link (CRITICAL untuk image upload)
echo "🔗 Creating storage link..."
php artisan storage:link --force 2>/dev/null || echo "   ⚠️  Storage link already exists or failed"

# Step 2: Ensure thumbnails directory exists
echo "📁 Ensuring thumbnails directory exists..."
mkdir -p storage/app/public/thumbnails
chmod 775 storage/app/public/thumbnails

# Step 3: Set proper permissions
echo "🔐 Setting storage permissions..."
chmod -R 775 storage 2>/dev/null || echo "   ⚠️  Permission setting skipped"
chmod -R 775 bootstrap/cache 2>/dev/null || echo "   ⚠️  Permission setting skipped"

# Step 4: Clear view cache (untuk memastikan blade templates fresh)
echo "🧹 Clearing view cache..."
php artisan view:clear

# Step 5: Verify storage setup
echo "✅ Verifying storage setup..."
if [ -L "public/storage" ] || [ -d "public/storage" ]; then
    echo "   ✓ Storage link OK"
else
    echo "   ✗ Storage link MISSING - trying to recreate..."
    php artisan storage:link --force
fi

if [ -d "storage/app/public/thumbnails" ]; then
    echo "   ✓ Thumbnails directory OK"
else
    echo "   ✗ Thumbnails directory MISSING - creating..."
    mkdir -p storage/app/public/thumbnails
fi

echo "✅ Startup completed!"
echo "🌐 Starting web server..."
echo ""

# Step 6: Start the web server
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
