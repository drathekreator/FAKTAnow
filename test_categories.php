<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Categories ===\n\n";

$categories = App\Models\Category::all();

echo "Total Categories: " . $categories->count() . "\n\n";

echo "Categories List:\n";
foreach ($categories as $category) {
    echo "- {$category->name} (slug: {$category->slug}, id: {$category->id})\n";
}

echo "\n=== Test Complete ===\n";
