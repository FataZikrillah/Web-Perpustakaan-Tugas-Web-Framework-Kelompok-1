<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

// Untuk model Admin
class Admin extends Authenticatable
{
    use HasFactory;

    // protected $table = 'admins'; 

    // atribut yang dapat diisi 
    protected $fillable = [
        'nama',
        'email',
        'password',
    ];

}