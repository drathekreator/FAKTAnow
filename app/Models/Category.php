<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Category
 * 
 * Model ini merepresentasikan tabel 'categories' yang menyimpan kategori artikel.
 * 
 * Kolom utama:
 * - name: Nama kategori (contoh: Teknologi, Olahraga, Politik)
 * - slug: URL-friendly version dari nama (untuk routing)
 * 
 * Relasi:
 * - Has Many Articles: Setiap kategori bisa memiliki banyak artikel
 */
class Category extends Model
{
    use HasFactory;

    /**
     * Kolom yang dilindungi dari mass assignment
     * Semua kolom bisa diisi kecuali 'id'
     * 
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Relasi ke model Article
     * Mendefinisikan bahwa setiap kategori bisa memiliki banyak artikel
     * 
     * Contoh penggunaan:
     * $category->articles; // Mendapatkan semua artikel dalam kategori ini
     * 
     * @return HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
