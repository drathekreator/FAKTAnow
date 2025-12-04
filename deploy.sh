#!/bin/bash

# FAKTAnow Deployment Script for Zeabur
# This script prepares the application for production deployment

echo "🚀 Starting FAKTAnow Deployment Process..."

# Step 1: Install Dependencies
echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

echo "📦 Installing NPM dependencies..."
npm install

# Step 2: Build Assets
echo "🔨 Building frontend assets..."
npm run build

# Step 3: Clear all caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Step 4: Run Migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Step 5: Create Storage Link
echo "🔗 Creating storage link..."
php artisan storage:link

# Step 6: Seed Database (if needed)
echo "🌱 Seeding database..."
php artisan db:seed --force

# Step 7: Optimize Application
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 8: Set Permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "✅ Deployment completed successfully!"
echo "🌐 Your application is ready to serve!"
