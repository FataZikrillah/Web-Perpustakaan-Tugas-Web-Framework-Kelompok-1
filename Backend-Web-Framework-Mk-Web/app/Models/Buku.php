<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';

    protected $fillable = [
        'judul',
        'cover_buku',
        'isbn',
        'tahun_terbit',
        'stok',
        'kategori_id',
        'penerbit_id',
    ];

    protected $casts = [
        'tahun_terbit' => 'integer',
        'stok' => 'integer',
        'created_at' => 'datetime',
    ];

    // Relasi: Buku dikategorikan oleh Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relasi: Buku diterbitkan oleh Penerbit
    public function penerbit()
    {
        return $this->belongsTo(Penerbit::class, 'penerbit_id');
    }

    // Relasi: Buku ditulis oleh banyak Author (Many-to-Many)
    public function authors()
    {
        return $this->belongsToMany(Author::class, 'buku_authors', 'buku_id', 'author_id')
                    ->withTimestamps();
    }

    // Relasi: Buku dipinjam dalam banyak Peminjaman
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }

    // Relasi: Buku dipinjam oleh banyak Anggota melalui Peminjaman
    public function peminjams()
    {
        return $this->belongsToMany(Anggota::class, 'peminjamans', 'buku_id', 'anggota_id')
                    ->withPivot('id', 'tanggal_pinjam', 'tanggal_kembali', 'tanggal_dikembalikan', 'status')
                    ->withTimestamps();
    }

    // Scope untuk buku yang tersedia (stok > 0)
    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    // Accessor untuk status ketersediaan
    public function getTersediaAttribute()
    {
        return $this->stok > 0;
    }
}
