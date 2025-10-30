<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anggotas = Anggota::with('peminjamans')->get();
        return response()->json([
            'success' => true,
            'data' => $anggotas
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nim' => 'required|string|max:20|unique:anggotas,nim',
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:anggotas,email',
                'alamat' => 'nullable|string',
                'telepon' => 'nullable|string|max:15',
                'status_keanggotaan' => 'nullable|in:Aktif,Non-Aktif,Diblokir',
                'foto_profil' => 'nullable|string',
            ]);

            $anggota = Anggota::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Anggota berhasil ditambahkan',
                'data' => $anggota
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
        $anggota = Anggota::with('peminjamans.buku')->find($id);

        if (!$anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Anggota tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $anggota
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $anggota = Anggota::find($id);

        if (!$anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Anggota tidak ditemukan'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'nim' => 'sometimes|required|string|max:20|unique:anggotas,nim,' . $id,
                'nama' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:anggotas,email,' . $id,
                'alamat' => 'nullable|string',
                'telepon' => 'nullable|string|max:15',
                'status_keanggotaan' => 'nullable|in:Aktif,Non-Aktif,Diblokir',
                'foto_profil' => 'nullable|string',
            ]);

            $anggota->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Anggota berhasil diupdate',
                'data' => $anggota
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
        $anggota = Anggota::find($id);

        if (!$anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Anggota tidak ditemukan'
            ], 404);
        }

        $anggota->delete();

        return response()->json([
            'success' => true,
            'message' => 'Anggota berhasil dihapus'
        ], 200);
    }
}
