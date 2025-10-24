<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerbit extends Model
{
    use HasFactory;

    protected $table = 'penerbits';

    protected $fillable = [
        'nama_penerbit',
        'alamat',
        'telepon',
        'email',
    ];

    // Relasi: Penerbit menerbitkan banyak Buku
    public function bukus()
    {
        return $this->hasMany(Buku::class, 'penerbit_id');
    }
}
