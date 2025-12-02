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
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // ID Primary Key (comment_id)

            // 🔑 Foreign Key ke tabel 'users' (Pengirim Komentar)
            $table->foreignId('user_id')
                  ->constrained('users') // Merujuk ke 'id' di tabel 'users'
                  ->onDelete('cascade'); // Jika user dihapus, komentarnya juga terhapus

            // 🔑 Foreign Key ke tabel 'articles' (Artikel yang Dikomentari)
            $table->foreignId('article_id')
                  ->constrained('articles') // Merujuk ke 'id' di tabel 'articles'
                  ->onDelete('cascade'); // Jika artikel dihapus, komentarnya juga terhapus
            
            $table->text('content'); // Isi/teks komentar
            $table->boolean('is_approved')->default(false); // Kolom untuk moderasi komentar
            
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};