<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Politik', 'slug' => 'politik'],
            ['name' => 'Ekonomi', 'slug' => 'ekonomi'],
            ['name' => 'Teknologi', 'slug' => 'teknologi'],
            ['name' => 'Olahraga', 'slug' => 'olahraga'],
            ['name' => 'Hiburan', 'slug' => 'hiburan'],
            ['name' => 'Kesehatan', 'slug' => 'kesehatan'],
            ['name' => 'Pendidikan', 'slug' => 'pendidikan'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::firstOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}
