<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $table = 'authors';

    protected $fillable = [
        'nama_author',
        'biografi',
        'email',
    ];

    // Relasi: Author menulis banyak Buku (Many-to-Many)
    public function bukus()
    {
        return $this->belongsToMany(Buku::class, 'buku_authors', 'author_id', 'buku_id')
                    ->withTimestamps();
    }
}
