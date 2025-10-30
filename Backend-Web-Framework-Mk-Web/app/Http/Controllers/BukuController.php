<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bukus = Buku::with(['kategori', 'penerbit', 'authors'])->get();
        return response()->json([
            'success' => true,
            'data' => $bukus
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'cover_buku' => 'nullable|string',
                'isbn' => 'nullable|string|max:20|unique:bukus,isbn',
                'tahun_terbit' => 'nullable|integer',
                'stok' => 'nullable|integer|min:0',
                'kategori_id' => 'nullable|exists:kategoris,id',
                'penerbit_id' => 'nullable|exists:penerbits,id',
            ]);

            $buku = Buku::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil ditambahkan',
                'data' => $buku->load(['kategori', 'penerbit', 'authors'])
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buku = Buku::with(['kategori', 'penerbit', 'authors', 'peminjamans'])->find($id);

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $buku
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'judul' => 'sometimes|required|string|max:255',
                'cover_buku' => 'nullable|string',
                'isbn' => 'nullable|string|max:20|unique:bukus,isbn,' . $id,
                'tahun_terbit' => 'nullable|integer',
                'stok' => 'nullable|integer|min:0',
                'kategori_id' => 'nullable|exists:kategoris,id',
                'penerbit_id' => 'nullable|exists:penerbits,id',
            ]);

            $buku->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil diupdate',
                'data' => $buku->load(['kategori', 'penerbit', 'authors'])
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dihapus'
        ], 200);
    }
}
