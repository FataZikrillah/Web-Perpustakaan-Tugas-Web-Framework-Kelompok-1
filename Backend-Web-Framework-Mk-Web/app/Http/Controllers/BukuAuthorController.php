<?php

namespace App\Http\Controllers;

use App\Models\BukuAuthor;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BukuAuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bukuAuthors = BukuAuthor::with(['buku', 'author'])->get();
        return response()->json([
            'success' => true,
            'data' => $bukuAuthors
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'buku_id' => 'required|exists:bukus,id',
                'author_id' => 'required|exists:authors,id',
            ]);

            // Check if relationship already exists
            $exists = BukuAuthor::where('buku_id', $validated['buku_id'])
                                ->where('author_id', $validated['author_id'])
                                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Relasi buku dan author sudah ada'
                ], 422);
            }

            $bukuAuthor = BukuAuthor::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Relasi buku-author berhasil ditambahkan',
                'data' => $bukuAuthor->load(['buku', 'author'])
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
        $bukuAuthor = BukuAuthor::with(['buku', 'author'])->find($id);

        if (!$bukuAuthor) {
            return response()->json([
                'success' => false,
                'message' => 'Relasi buku-author tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $bukuAuthor
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bukuAuthor = BukuAuthor::find($id);

        if (!$bukuAuthor) {
            return response()->json([
                'success' => false,
                'message' => 'Relasi buku-author tidak ditemukan'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'buku_id' => 'sometimes|required|exists:bukus,id',
                'author_id' => 'sometimes|required|exists:authors,id',
            ]);

            // Check if new relationship already exists
            if (isset($validated['buku_id']) || isset($validated['author_id'])) {
                $bukuId = $validated['buku_id'] ?? $bukuAuthor->buku_id;
                $authorId = $validated['author_id'] ?? $bukuAuthor->author_id;

                $exists = BukuAuthor::where('buku_id', $bukuId)
                                    ->where('author_id', $authorId)
                                    ->where('id', '!=', $id)
                                    ->exists();

                if ($exists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Relasi buku dan author sudah ada'
                    ], 422);
                }
            }

            $bukuAuthor->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Relasi buku-author berhasil diupdate',
                'data' => $bukuAuthor->load(['buku', 'author'])
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
        $bukuAuthor = BukuAuthor::find($id);

        if (!$bukuAuthor) {
            return response()->json([
                'success' => false,
                'message' => 'Relasi buku-author tidak ditemukan'
            ], 404);
        }

        $bukuAuthor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Relasi buku-author berhasil dihapus'
        ], 200);
    }
}
