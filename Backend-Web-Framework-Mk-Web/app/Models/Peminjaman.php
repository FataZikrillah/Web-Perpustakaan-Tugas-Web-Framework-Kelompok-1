<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'anggota_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali' => 'datetime',
        'tanggal_dikembalikan' => 'datetime',
    ];

    // Relasi: Peminjaman dilakukan oleh Anggota
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    // Relasi: Peminjaman untuk Buku tertentu
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    // Scope untuk peminjaman yang masih dipinjam
    public function scopeDipinjam($query)
    {
        return $query->where('status', 'Dipinjam');
    }

    // Scope untuk peminjaman yang terlambat
    public function scopeTerlambat($query)
    {
        return $query->where('status', 'Terlambat')
                     ->orWhere(function($q) {
                         $q->where('status', 'Dipinjam')
                           ->where('tanggal_kembali', '<', now());
                     });
    }

    // Accessor untuk mengecek apakah terlambat
    public function getTerlambatAttribute()
    {
        if ($this->status === 'Dipinjam' && $this->tanggal_kembali) {
            return now()->isAfter($this->tanggal_kembali);
        }
        return false;
    }
}