<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Like
 * 
 * Model ini merepresentasikan tabel 'likes' yang menyimpan data like/suka
 * dari user terhadap artikel tertentu.
 * 
 * Relasi:
 * - Belongs To User: Setiap like dimiliki oleh satu user
 * - Belongs To Article: Setiap like terkait dengan satu artikel
 */
class Like extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara mass assignment
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',      // ID user yang memberikan like
        'article_id',   // ID artikel yang di-like
    ];

    /**
     * Relasi ke model User
     * Mendefinisikan bahwa setiap like dimiliki oleh satu user
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Article
     * Mendefinisikan bahwa setiap like terkait dengan satu artikel
     * 
     * @return BelongsTo
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
