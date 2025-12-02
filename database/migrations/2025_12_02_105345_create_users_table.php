<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan Schema::create diikuti dengan kurung kurawal penutup } di akhir.
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ID Primary Key (id, auto-increment)
            $table->string('name');
            $table->string('email')->unique(); // Email harus unik
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('member'); // Kolom role
            $table->rememberToken();
            $table->timestamps(); // created_at dan updated_at
        });
    }

    // ---
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Untuk membalikkan pembuatan tabel (rollback), Anda cukup menghapus tabel.
        Schema::dropIfExists('users');
    }
};