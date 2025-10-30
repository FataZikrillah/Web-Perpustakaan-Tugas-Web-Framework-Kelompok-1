<?php

namespace App\Http\Controllers;

use App\Models\Penerbit;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PenerbitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penerbits = Penerbit::with('bukus')->get();
        return response()->json([
            'success' => true,
            'data' => $penerbits
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_penerbit' => 'required|string|max:255',
                'alamat' => 'nullable|string',
                'telepon' => 'nullable|string|max:15',
                'email' => 'nullable|email',
            ]);

            $penerbit = Penerbit::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Penerbit berhasil ditambahkan',
                'data' => $penerbit
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
        $penerbit = Penerbit::with('bukus')->find($id);

        if (!$penerbit) {
            return response()->json([
                'success' => false,
                'message' => 'Penerbit tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $penerbit
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $penerbit = Penerbit::find($id);

        if (!$penerbit) {
            return response()->json([
                'success' => false,
                'message' => 'Penerbit tidak ditemukan'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'nama_penerbit' => 'sometimes|required|string|max:255',
                'alamat' => 'nullable|string',
                'telepon' => 'nullable|string|max:15',
                'email' => 'nullable|email',
            ]);

            $penerbit->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Penerbit berhasil diupdate',
                'data' => $penerbit
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
        $penerbit = Penerbit::find($id);

        if (!$penerbit) {
            return response()->json([
                'success' => false,
                'message' => 'Penerbit tidak ditemukan'
            ], 404);
        }

        $penerbit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Penerbit berhasil dihapus'
        ], 200);
    }
}
