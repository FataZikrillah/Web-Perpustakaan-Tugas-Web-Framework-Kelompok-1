
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenerbitController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\BukuAuthorController;
use App\Http\Controllers\PeminjamanController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Resources
Route::apiResource('admins', AdminController::class);
Route::apiResource('kategori', KategoriController::class);
Route::apiResource('penerbit', PenerbitController::class);
Route::apiResource('authors', AuthorController::class);
Route::apiResource('anggota', AnggotaController::class);
Route::apiResource('buku', BukuController::class);
Route::apiResource('buku-authors', BukuAuthorController::class);
Route::apiResource('peminjaman', PeminjamanController::class);
