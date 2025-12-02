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
        Schema::create('articles', function (Blueprint $table) {
            $table->id(); // ID Primary Key (article_id)

            // 🔑 Foreign Key ke tabel 'users' (penulis artikel)
            // Menggunakan foreignId() adalah cara modern di Laravel
            $table->foreignId('user_id')
                  ->constrained('users') // Memastikan kolom ini merujuk ke 'id' di tabel 'users'
                  ->onDelete('cascade'); // Jika user dihapus, artikelnya juga terhapus
            
            // Kolom Konten Artikel
            $table->string('title', 150)->unique(); // Judul, dibatasi 150 karakter dan harus unik
            $table->string('slug')->unique();       // Slug (untuk URL yang ramah SEO), harus unik
            $table->text('content');                // Konten utama artikel (teks panjang)
            $table->string('thumbnail_url')->nullable(); // Opsional: URL gambar thumbnail
            $table->integer('views')->default(0);   // Jumlah dilihat, default 0
            
            // Status Artikel (untuk manajemen draft/publish)
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};