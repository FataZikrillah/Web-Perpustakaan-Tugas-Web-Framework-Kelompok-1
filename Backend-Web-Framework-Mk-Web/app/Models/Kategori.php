<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';

    // atribut yang dapat diisi
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    // Relasi: Kategori mengkategorikan banyak Buku
    public function bukus()
    {
        return $this->hasMany(Buku::class, 'kategori_id');
    }
}
