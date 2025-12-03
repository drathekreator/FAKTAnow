<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * UserSeeder
 * 
 * Seeder ini digunakan untuk mengisi tabel 'users' dengan data user awal
 * untuk keperluan testing dan development.
 * 
 * Membuat 3 jenis user:
 * 1. Admin - Akses penuh ke semua fitur
 * 2. Editor - Bisa membuat dan mengelola artikel
 * 3. Member - Hanya bisa membaca, like, dan komentar
 * 
 * Cara menjalankan:
 * php artisan db:seed --class=UserSeeder
 */
class UserSeeder extends Seeder
{
    /**
     * Menjalankan database seeder untuk user
     * 
     * Method ini akan membuat:
     * - 1 Admin utama dengan kredensial yang sudah diketahui
     * - 1 Editor utama dengan kredensial yang sudah diketahui
     * - 1 Member utama dengan kredensial yang sudah diketahui
     * - 2 Admin random tambahan (menggunakan factory)
     * - 3 Editor random tambahan (menggunakan factory)
     * - 10 Member random tambahan (menggunakan factory)
     * 
     * Semua password default adalah 'password'
     * 
     * @return void
     */
    public function run(): void
    {
        // =====================================================================
        // BAGIAN 1: Buat Akun Spesifik untuk Testing
        // =====================================================================
        // Akun-akun ini memiliki kredensial yang sudah diketahui
        // sehingga mudah untuk login saat testing

        // Admin Utama
        // Email: admin@portalberita.com
        // Password: password
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@portalberita.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Editor Utama
        // Email: editor@portalberita.com
        // Password: password
        User::create([
            'name' => 'Editor Portal',
            'email' => 'editor@portalberita.com',
            'password' => Hash::make('password'),
            'role' => 'editor',
            'email_verified_at' => now(),
        ]);

        // Member Utama
        // Email: member@portalberita.com
        // Password: password
        User::create([
            'name' => 'Member Biasa',
            'email' => 'member@portalberita.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);
        
        // =====================================================================
        // BAGIAN 2: Buat Akun Random menggunakan Factory
        // =====================================================================
        // Factory akan generate data random untuk testing yang lebih realistis

        // Buat 2 akun Admin random tambahan
        User::factory(2)->admin()->create(); 
        
        // Buat 3 akun Editor random tambahan
        User::factory(3)->editor()->create(); 

        // Buat 10 akun Member random
        User::factory(10)->create(); 
        
        // Tampilkan pesan sukses di console
        $this->command->info('✓ Users seeded successfully!');
        $this->command->info('Total users: ' . User::count());
    }
}