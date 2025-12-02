<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    // --- Mass Assignment Protection ---
    // Kolom yang dapat diisi secara mass assignment (sesuai dengan kolom di migration)
    protected $fillable = [
        'user_id',
        'article_id',
        'content',
        'is_approved',
    ];

    // --- Relasi Eloquent ---

    /**
     * Relasi: Comment dimiliki oleh satu User (Pengirim).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Comment dimiliki oleh satu Article.
     */
    public function article(): BelongsTo
    {
        // Mendefinisikan bahwa kolom article_id di tabel comments merujuk ke id di tabel articles
        return $this->belongsTo(Article::class);
    }
}