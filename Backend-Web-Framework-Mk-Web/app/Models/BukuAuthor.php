<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuAuthor extends Model
{
    use HasFactory;

    protected $table = 'buku_authors';

    protected $fillable = [
        'buku_id',
        'author_id',
    ];

    // Relasi: Pivot ke Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    // Relasi: Pivot ke Author
    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }
}
