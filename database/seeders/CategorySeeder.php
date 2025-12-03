<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * CategorySeeder
 * 
 * Seeder ini digunakan untuk mengisi tabel 'categories' dengan data kategori awal.
 * Kategori ini akan digunakan untuk mengklasifikasikan artikel.
 * 
 * Cara menjalankan:
 * php artisan db:seed --class=CategorySeeder
 * 
 * Atau jalankan semua seeder:
 * php artisan db:seed
 */
class CategorySeeder extends Seeder
{
    /**
     * Menjalankan database seeder untuk kategori
     * 
     * Method ini akan mengisi tabel categories dengan 8 kategori default:
     * - Politik
     * - Ekonomi
     * - Teknologi
     * - Olahraga
     * - Hiburan
     * - Kesehatan
     * - Pendidikan
     * - Regional
     * 
     * Menggunakan updateOrCreate() agar tidak duplikat jika dijalankan berulang kali.
     * 
     * @return void
     */
    public function run(): void
    {
        // Array berisi data kategori yang akan di-seed
        $categories = [
            [
                'name' => 'Politik',
                'slug' => 'politik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ekonomi',
                'slug' => 'ekonomi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Teknologi',
                'slug' => 'teknologi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Olahraga',
                'slug' => 'olahraga',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hiburan',
                'slug' => 'hiburan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kesehatan',
                'slug' => 'kesehatan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Regional',
                'slug' => 'regional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Loop setiap kategori dan insert/update ke database
        // updateOrCreate() akan:
        // - Update jika kategori dengan slug yang sama sudah ada
        // - Create baru jika belum ada
        // Ini mencegah duplikasi data jika seeder dijalankan berulang kali
        foreach ($categories as $category) {
            \App\Models\Category::updateOrCreate(
                ['slug' => $category['slug']],  // Kondisi pencarian
                $category                        // Data yang akan di-insert/update
            );
        }
        
        // Tampilkan pesan sukses di console
        $this->command->info('✓ Categories seeded successfully!');
        $this->command->info('Total categories: ' . \App\Models\Category::count());
    }
}
