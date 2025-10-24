<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggotas';

    protected $fillable = [
        'nim',
        'nama',
        'email',
        'alamat',
        'telepon',
        'status_keanggotaan',
        'foto_profil',
    ];


    // Relasi: Anggota memiliki banyak Peminjaman
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'anggota_id');
    }

    // Relasi: Anggota meminjam banyak Buku melalui Peminjaman
    public function bukusDipinjam()
    {
        return $this->belongsToMany(Buku::class, 'peminjamans', 'anggota_id', 'buku_id')
                    ->withPivot('id', 'tanggal_pinjam', 'tanggal_kembali', 'tanggal_dikembalikan', 'status')
                    ->withTimestamps();
    }
}
