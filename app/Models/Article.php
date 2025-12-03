<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Article
 * 
 * Model ini merepresentasikan tabel 'articles' yang menyimpan data artikel/berita.
 * 
 * Kolom utama:
 * - user_id: ID penulis artikel
 * - category_id: ID kategori artikel
 * - title: Judul artikel
 * - slug: URL-friendly version dari judul (untuk SEO)
 * - content: Isi artikel
 * - thumbnail_url: URL gambar thumbnail
 * - views: Jumlah views/pembaca
 * - status: Status artikel (draft, pending, published, rejected)
 * 
 * Relasi:
 * - Belongs To User: Setiap artikel dimiliki oleh satu penulis
 * - Belongs To Category: Setiap artikel masuk dalam satu kategori
 * - Has Many Comments: Setiap artikel bisa memiliki banyak komentar
 * - Has Many Likes: Setiap artikel bisa memiliki banyak likes
 */
class Article extends Model
{
    use HasFactory;
    
    /**
     * Kolom yang dapat diisi secara mass assignment
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',          // ID penulis
        'category_id',      // ID kategori
        'title',            // Judul artikel
        'slug',             // URL slug (contoh: artikel-saya)
        'content',          // Isi artikel
        'thumbnail_url',    // URL gambar thumbnail
        'views',            // Jumlah views
        'status',           // Status: draft, pending, published, rejected
    ];

    /**
     * Override route key untuk menggunakan slug di URL
     * 
     * Method ini memaksa Laravel menggunakan kolom 'slug' alih-alih 'id'
     * saat melakukan route model binding.
     * 
     * Contoh URL:
     * - Tanpa override: /article/123
     * - Dengan override: /article/judul-artikel-saya
     * 
     * @return string Nama kolom yang digunakan untuk route binding
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ========================================================================
    // RELASI ELOQUENT
    // ========================================================================

    /**
     * Relasi ke model Category
     * Mendefinisikan bahwa setiap artikel masuk dalam satu kategori
     * 
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke model User
     * Mendefinisikan bahwa setiap artikel dimiliki oleh satu penulis
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Comment
     * Mendefinisikan bahwa setiap artikel bisa memiliki banyak komentar
     * 
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relasi ke model Like
     * Mendefinisikan bahwa setiap artikel bisa memiliki banyak likes
     * 
     * @return HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    // ========================================================================
    // HELPER METHODS
    // ========================================================================

    /**
     * Cek apakah user tertentu sudah like artikel ini
     * 
     * Method ini berguna untuk menampilkan status like di UI
     * (misalnya tombol like berwarna merah jika sudah like)
     * 
     * @param User|null $user User yang akan dicek
     * @return bool True jika user sudah like, false jika belum
     */
    public function isLikedBy($user): bool
    {
        // Jika user tidak login, return false
        if (!$user) return false;
        
        // Cek apakah ada record like dari user ini untuk artikel ini
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Mendapatkan total jumlah likes artikel ini
     * 
     * Method ini menghitung jumlah likes dari relasi likes()
     * 
     * @return int Jumlah likes
     */
    public function likesCount(): int
    {
        return $this->likes()->count();
    }
}