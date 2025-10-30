<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $peminjamans = Peminjaman::with(['anggota', 'buku'])->get();
        return response()->json([
            'success' => true,
            'data' => $peminjamans
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'anggota_id' => 'required|exists:anggotas,id',
                'buku_id' => 'required|exists:bukus,id',
                'tanggal_pinjam' => 'nullable|date',
                'tanggal_kembali' => 'nullable|date',
                'status' => 'nullable|in:Dipinjam,Dikembalikan,Terlambat,Hilang',
            ]);

            // Check if book is available
            $buku = Buku::find($validated['buku_id']);
            if ($buku->stok <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok buku tidak tersedia'
                ], 422);
            }

            // Set default values if not provided
            if (!isset($validated['tanggal_pinjam'])) {
                $validated['tanggal_pinjam'] = Carbon::now();
            }
            if (!isset($validated['tanggal_kembali'])) {
                $validated['tanggal_kembali'] = Carbon::parse($validated['tanggal_pinjam'])->addDays(7);
            }

            $peminjaman = Peminjaman::create($validated);

            // Decrease book stock
            $buku->decrement('stok');

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil ditambahkan',
                'data' => $peminjaman->load(['anggota', 'buku'])
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
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->find($id);

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $peminjaman
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman tidak ditemukan'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'anggota_id' => 'sometimes|required|exists:anggotas,id',
                'buku_id' => 'sometimes|required|exists:bukus,id',
                'tanggal_pinjam' => 'nullable|date',
                'tanggal_kembali' => 'nullable|date',
                'tanggal_dikembalikan' => 'nullable|date',
                'status' => 'nullable|in:Dipinjam,Dikembalikan,Terlambat,Hilang',
            ]);

            // If status changed to Dikembalikan, increase book stock
            if (isset($validated['status']) && $validated['status'] === 'Dikembalikan' && $peminjaman->status !== 'Dikembalikan') {
                $buku = Buku::find($peminjaman->buku_id);
                $buku->increment('stok');
                
                if (!isset($validated['tanggal_dikembalikan'])) {
                    $validated['tanggal_dikembalikan'] = Carbon::now();
                }
            }

            $peminjaman->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Peminjaman berhasil diupdate',
                'data' => $peminjaman->load(['anggota', 'buku'])
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
        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman tidak ditemukan'
            ], 404);
        }

        // If book is still borrowed, return the stock
        if ($peminjaman->status === 'Dipinjam') {
            $buku = Buku::find($peminjaman->buku_id);
            $buku->increment('stok');
        }

        $peminjaman->delete();

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil dihapus'
        ], 200);
    }
}
