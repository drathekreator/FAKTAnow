<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan Model User di-import
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- 1. Buat Akun Spesifik (Untuk Pengujian Login) ---

        // Admin Utama
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@portalberita.com',
            'password' => Hash::make('password'), // Password: 'password'
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Editor Utama
        User::create([
            'name' => 'Editor Portal',
            'email' => 'editor@portalberita.com',
            'password' => Hash::make('password'), // Password: 'password'
            'role' => 'editor',
            'email_verified_at' => now(),
        ]);

        // Member Utama
        User::create([
            'name' => 'Member Biasa',
            'email' => 'member@portalberita.com',
            'password' => Hash::make('password'), // Password: 'password'
            'role' => 'member',
            'email_verified_at' => now(),
        ]);
        
        // --- 2. Buat Akun Acak (Menggunakan Factory) ---

        // Buat 2 akun Admin acak tambahan menggunakan state 'admin'
        User::factory(2)->admin()->create(); 
        
        // Buat 3 akun Editor acak tambahan menggunakan state 'editor'
        User::factory(3)->editor()->create(); 

        // Buat 10 akun Member acak lainnya
        User::factory(10)->create(); 
    }
}