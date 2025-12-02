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
    // Kolom-kolom yang boleh diisi melalui mass assignment 
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'thumbnail_url',
        'views',
        'status',
    ];

    // --- Relasi Eloquent ---

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