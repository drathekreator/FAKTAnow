<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder
 * 
 * Seeder utama yang menjalankan semua seeder lainnya.
 * File ini adalah entry point untuk seeding database.
 * 
 * Cara menjalankan:
 * php artisan db:seed
 * 
 * Atau dengan fresh migration:
 * php artisan migrate:fresh --seed
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Menjalankan semua database seeder
     * 
     * Method ini akan menjalankan seeder dalam urutan yang benar:
     * 1. CategorySeeder - Harus dijalankan pertama karena artikel membutuhkan kategori
     * 2. User test account - Membuat user untuk testing
     * 
     * Urutan penting karena ada relasi foreign key antar tabel.
     * 
     * @return void
     */
    public function run(): void
    {
        // STEP 1: Seed Categories terlebih dahulu
        // Kategori harus ada sebelum artikel karena artikel membutuhkan category_id
        $this->call(CategorySeeder::class);
        
        // STEP 2: Buat user untuk testing
        // Uncomment baris di bawah untuk membuat 10 user random
        // User::factory(10)->create();

        // Buat satu user test dengan kredensial yang sudah diketahui
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // STEP 3: Seed Users dengan role berbeda (opsional)
        // Uncomment baris di bawah untuk menjalankan UserSeeder
        // UserSeeder akan membuat admin, editor, dan member
        // $this->call(UserSeeder::class);
        
        // STEP 4: Seed data lainnya (opsional)
        // Jika Anda sudah punya ArticleSeeder dan CommentSeeder, panggil di sini
        // $this->call(ArticleSeeder::class); 
        // $this->call(CommentSeeder::class);
    }
}
