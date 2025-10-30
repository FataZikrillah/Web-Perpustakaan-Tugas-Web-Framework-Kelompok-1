<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::with('bukus')->get();
        return response()->json([
            'success' => true,
            'data' => $authors
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_author' => 'required|string|max:255',
                'biografi' => 'nullable|string',
                'email' => 'nullable|email',
            ]);

            $author = Author::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Author berhasil ditambahkan',
                'data' => $author
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
        $author = Author::with('bukus')->find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $author
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author tidak ditemukan'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'nama_author' => 'sometimes|required|string|max:255',
                'biografi' => 'nullable|string',
                'email' => 'nullable|email',
            ]);

            $author->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Author berhasil diupdate',
                'data' => $author
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
        $author = Author::find($id);

        if (!$author) {
            return response()->json([
                'success' => false,
                'message' => 'Author tidak ditemukan'
            ], 404);
        }

        $author->delete();

        return response()->json([
            'success' => true,
            'message' => 'Author berhasil dihapus'
        ], 200);
    }
}
