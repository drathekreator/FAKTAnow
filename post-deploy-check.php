#!/usr/bin/env php
<?php

/**
 * Post-Deployment Health Check Script
 * 
 * This script checks if the application is properly configured
 * and ready for production use.
 */

echo "🔍 FAKTAnow Post-Deployment Health Check\n";
echo "========================================\n\n";

$errors = [];
$warnings = [];
$success = [];

// Check 1: PHP Version
echo "Checking PHP version... ";
if (version_compare(PHP_VERSION, '8.2.0', '>=')) {
    echo "✅ PHP " . PHP_VERSION . "\n";
    $success[] = "PHP version is compatible";
} else {
    echo "❌ FAIL\n";
    $errors[] = "PHP version must be >= 8.2.0, current: " . PHP_VERSION;
}

// Check 2: Required PHP Extensions
echo "Checking PHP extensions... ";
$required_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath'];
$missing_extensions = [];

foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missing_extensions[] = $ext;
    }
}

if (empty($missing_extensions)) {
    echo "✅ All required extensions loaded\n";
    $success[] = "All PHP extensions available";
} else {
    echo "❌ FAIL\n";
    $errors[] = "Missing PHP extensions: " . implode(', ', $missing_extensions);
}

// Check 3: .env file exists
echo "Checking .env file... ";
if (file_exists(__DIR__ . '/.env')) {
    echo "✅ Found\n";
    $success[] = ".env file exists";
} else {
    echo "❌ FAIL\n";
    $errors[] = ".env file not found";
}

// Check 4: Storage directory writable
echo "Checking storage permissions... ";
if (is_writable(__DIR__ . '/storage')) {
    echo "✅ Writable\n";
    $success[] = "Storage directory is writable";
} else {
    echo "❌ FAIL\n";
    $errors[] = "Storage directory is not writable";
}

// Check 5: Bootstrap cache writable
echo "Checking bootstrap/cache permissions... ";
if (is_writable(__DIR__ . '/bootstrap/cache')) {
    echo "✅ Writable\n";
    $success[] = "Bootstrap cache is writable";
} else {
    echo "❌ FAIL\n";
    $errors[] = "Bootstrap cache directory is not writable";
}

// Check 6: Storage link exists
echo "Checking storage link... ";
$storageLink = __DIR__ . '/public/storage';
if (is_link($storageLink) || is_dir($storageLink)) {
    echo "✅ Exists\n";
    $success[] = "Storage link created";
    
    // Additional check: Verify thumbnails directory
    echo "Checking thumbnails directory... ";
    $thumbnailsDir = __DIR__ . '/storage/app/public/thumbnails';
    if (is_dir($thumbnailsDir)) {
        if (is_writable($thumbnailsDir)) {
            echo "✅ Exists and writable\n";
            $success[] = "Thumbnails directory ready";
        } else {
            echo "⚠️  WARNING (not writable)\n";
            $warnings[] = "Thumbnails directory not writable. Run: chmod 775 storage/app/public/thumbnails";
        }
    } else {
        echo "⚠️  WARNING (not found)\n";
        $warnings[] = "Thumbnails directory not found. Run: mkdir -p storage/app/public/thumbnails";
    }
} else {
    echo "❌ FAIL\n";
    $errors[] = "Storage link not found. Run: php artisan storage:link";
}

// Check 7: Vendor directory exists
echo "Checking vendor directory... ";
if (is_dir(__DIR__ . '/vendor')) {
    echo "✅ Found\n";
    $success[] = "Composer dependencies installed";
} else {
    echo "❌ FAIL\n";
    $errors[] = "Vendor directory not found. Run: composer install";
}

// Check 8: Node modules exists
echo "Checking node_modules... ";
if (is_dir(__DIR__ . '/node_modules')) {
    echo "✅ Found\n";
    $success[] = "NPM dependencies installed";
} else {
    echo "⚠️  WARNING\n";
    $warnings[] = "node_modules not found. Run: npm install";
}

// Check 9: Built assets exist
echo "Checking built assets... ";
if (is_dir(__DIR__ . '/public/build')) {
    echo "✅ Found\n";
    $success[] = "Frontend assets built";
} else {
    echo "⚠️  WARNING\n";
    $warnings[] = "Built assets not found. Run: npm run build";
}

// Check 10: Database connection (if Laravel is available)
echo "Checking database connection... ";
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
    
    try {
        $app = require_once __DIR__ . '/bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        
        DB::connection()->getPdo();
        echo "✅ Connected\n";
        $success[] = "Database connection successful";
    } catch (Exception $e) {
        echo "❌ FAIL\n";
        $errors[] = "Database connection failed: " . $e->getMessage();
    }
} else {
    echo "⏭️  SKIP (vendor not found)\n";
}

// Summary
echo "\n========================================\n";
echo "📊 Summary\n";
echo "========================================\n";
echo "✅ Success: " . count($success) . "\n";
echo "⚠️  Warnings: " . count($warnings) . "\n";
echo "❌ Errors: " . count($errors) . "\n\n";

if (!empty($warnings)) {
    echo "⚠️  WARNINGS:\n";
    foreach ($warnings as $warning) {
        echo "   - $warning\n";
    }
    echo "\n";
}

if (!empty($errors)) {
    echo "❌ ERRORS:\n";
    foreach ($errors as $error) {
        echo "   - $error\n";
    }
    echo "\n";
    echo "❌ Deployment check FAILED!\n";
    exit(1);
}

echo "✅ All checks passed! Application is ready for production.\n";
exit(0);
