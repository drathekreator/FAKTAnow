<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Storage;

echo "=== Testing Storage Configuration ===\n\n";

// Test 1: Check storage link
echo "1. Storage Link: ";
$storageLink = public_path('storage');
if (file_exists($storageLink)) {
    echo "✓ EXISTS at " . $storageLink . "\n";
    if (is_link($storageLink)) {
        echo "   Type: Symbolic Link\n";
        echo "   Target: " . readlink($storageLink) . "\n";
    } else {
        echo "   Type: Directory\n";
    }
} else {
    echo "✗ NOT FOUND\n";
    echo "   Run: php artisan storage:link\n";
}

// Test 2: Check thumbnails directory
echo "\n2. Thumbnails Directory: ";
$thumbDir = storage_path('app/public/thumbnails');
if (is_dir($thumbDir)) {
    echo "✓ EXISTS at " . $thumbDir . "\n";
} else {
    echo "✗ NOT FOUND\n";
    echo "   Creating directory...\n";
    mkdir($thumbDir, 0775, true);
    echo "   ✓ Directory created\n";
}

// Test 3: Check write permission
echo "\n3. Write Permission: ";
$publicStorage = storage_path('app/public');
if (is_writable($publicStorage)) {
    echo "✓ WRITABLE\n";
} else {
    echo "✗ NOT WRITABLE\n";
    echo "   Run: chmod -R 775 storage\n";
}

// Test 4: Test file write
echo "\n4. Test File Write: ";
try {
    $testFile = 'test_' . time() . '.txt';
    $testContent = 'Test content at ' . date('Y-m-d H:i:s');
    
    Storage::disk('public')->put('thumbnails/' . $testFile, $testContent);
    echo "✓ SUCCESS\n";
    echo "   File: " . $testFile . "\n";
    echo "   Path: " . storage_path('app/public/thumbnails/' . $testFile) . "\n";
    
    // Verify file exists
    if (Storage::disk('public')->exists('thumbnails/' . $testFile)) {
        echo "   ✓ File verified in storage\n";
        
        // Check if accessible via URL
        $url = '/storage/thumbnails/' . $testFile;
        echo "   URL: " . url($url) . "\n";
    }
    
    // Clean up
    Storage::disk('public')->delete('thumbnails/' . $testFile);
    echo "   ✓ Test file cleaned up\n";
} catch (\Exception $e) {
    echo "✗ FAILED\n";
    echo "   Error: " . $e->getMessage() . "\n";
}

// Test 5: List existing thumbnails
echo "\n5. Existing Thumbnails:\n";
try {
    $files = Storage::disk('public')->files('thumbnails');
    if (empty($files)) {
        echo "   No thumbnails found\n";
    } else {
        echo "   Total: " . count($files) . " files\n";
        foreach ($files as $file) {
            $size = Storage::disk('public')->size($file);
            $sizeKB = round($size / 1024, 2);
            echo "   - " . basename($file) . " (" . $sizeKB . " KB)\n";
        }
    }
} catch (\Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

// Test 6: Check filesystem config
echo "\n6. Filesystem Configuration:\n";
echo "   Default Disk: " . config('filesystems.default') . "\n";
echo "   Public Disk Root: " . config('filesystems.disks.public.root') . "\n";
echo "   Public Disk URL: " . config('filesystems.disks.public.url') . "\n";

// Test 7: Check APP_URL
echo "\n7. Application URL:\n";
echo "   APP_URL: " . config('app.url') . "\n";
echo "   Storage URL: " . config('app.url') . '/storage' . "\n";

// Test 8: Check recent articles with thumbnails
echo "\n8. Recent Articles with Thumbnails:\n";
try {
    $articles = App\Models\Article::whereNotNull('thumbnail_url')
                                  ->latest()
                                  ->take(5)
                                  ->get(['id', 'title', 'thumbnail_url']);
    
    if ($articles->isEmpty()) {
        echo "   No articles with thumbnails found\n";
    } else {
        foreach ($articles as $article) {
            echo "   - Article #{$article->id}: " . substr($article->title, 0, 30) . "...\n";
            echo "     URL: {$article->thumbnail_url}\n";
            
            // Check if file exists
            $path = str_replace('/storage/', '', $article->thumbnail_url);
            if (Storage::disk('public')->exists($path)) {
                $size = Storage::disk('public')->size($path);
                echo "     ✓ File exists (" . round($size / 1024, 2) . " KB)\n";
            } else {
                echo "     ✗ File not found in storage\n";
            }
        }
    }
} catch (\Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
echo "\nRecommendations:\n";

$issues = [];

if (!file_exists(public_path('storage'))) {
    $issues[] = "Run: php artisan storage:link";
}

if (!is_dir(storage_path('app/public/thumbnails'))) {
    $issues[] = "Create thumbnails directory";
}

if (!is_writable(storage_path('app/public'))) {
    $issues[] = "Fix storage permissions: chmod -R 775 storage";
}

if (empty($issues)) {
    echo "✓ All checks passed! Storage is configured correctly.\n";
} else {
    echo "⚠ Issues found:\n";
    foreach ($issues as $issue) {
        echo "  - " . $issue . "\n";
    }
}

echo "\n";
