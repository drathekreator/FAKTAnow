<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Comment
 * 
 * Model ini merepresentasikan tabel 'comments' yang menyimpan komentar pada artikel.
 * 
 * Kolom utama:
 * - user_id: ID user yang memberikan komentar
 * - article_id: ID artikel yang dikomentari
 * - content: Isi komentar
 * - is_approved: Status approval komentar (true/false)
 * 
 * Relasi:
 * - Belongs To User: Setiap komentar dimiliki oleh satu user
 * - Belongs To Article: Setiap komentar terkait dengan satu artikel
 */
class Comment extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara mass assignment
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',       // ID user yang memberikan komentar
        'article_id',    // ID artikel yang dikomentari
        'content',       // Isi komentar
        'is_approved',   // Status approval (true = disetujui, false = menunggu moderasi)
    ];

    // ========================================================================
    // RELASI ELOQUENT
    // ========================================================================

    /**
     * Relasi ke model User
     * Mendefinisikan bahwa setiap komentar dimiliki oleh satu user (pemberi komentar)
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Article
     * Mendefinisikan bahwa setiap komentar terkait dengan satu artikel
     * 
     * @return BelongsTo
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}