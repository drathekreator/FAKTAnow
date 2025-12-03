<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasFactory;
    
    // --- Mass Assignment Protection ---
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'content',
        'thumbnail_url',
        'views',
        'status',
    ];

    // ------------------------------------------------------------------
    // PERBAIKAN KRITIS UNTUK 404 NOT FOUND (MODEL BINDING)
    // ------------------------------------------------------------------
    /**
     * Dapatkan kunci rute untuk model. Ini memaksa Laravel menggunakan
     * kolom 'slug' alih-alih 'id' di URL.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    // ------------------------------------------------------------------


    // --- Relasi Eloquent ---
    public function category(){
        return $this->belongsTo(Category::class);
    }
    /**
     * Relasi One-to-One/One-to-Many: Article dimiliki oleh satu User (Penulis).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi One-to-Many: Article memiliki banyak Comment.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}