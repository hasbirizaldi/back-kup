<?php

namespace App\Http\Controllers;

use App\Models\Spesialis;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SpesialisController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Spesialis::orderBy('nama')->get()
        ]);
    }

    /**
     * POST /api/spesialis
     * Tambah spesialis
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:spesialis,nama'
        ]);

        $spesialis = Spesialis::create([
            'nama' => $request->nama
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Spesialis berhasil ditambahkan',
            'data' => $spesialis
        ], 201);
    }

    /**
     * GET /api/spesialis/{id}
     * Detail spesialis
     */
    public function show($id)
    {
        $spesialis = Spesialis::find($id);

        if (!$spesialis) {
            return response()->json([
                'status' => false,
                'message' => 'Spesialis tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $spesialis
        ]);
    }

    /**
     * PUT /api/spesialis/{id}
     * Update spesialis
     */
    public function update(Request $request, $id)
    {
        $spesialis = Spesialis::find($id);

        if (!$spesialis) {
            return response()->json([
                'status' => false,
                'message' => 'Spesialis tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
                Rule::unique('spesialis', 'nama')->ignore($id)
            ]
        ]);

        $spesialis->update([
            'nama' => $request->nama
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Spesialis berhasil diupdate',
            'data' => $spesialis
        ]);
    }

    /**
     * DELETE /api/spesialis/{id}
     * Hapus spesialis
     */
    public function destroy($id)
    {
        $spesialis = Spesialis::find($id);

        if (!$spesialis) {
            return response()->json([
                'status' => false,
                'message' => 'Spesialis tidak ditemukan'
            ], 404);
        }

        // optional: cek apakah masih dipakai dokter
        if ($spesialis->dokters()->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Spesialis masih digunakan oleh dokter'
            ], 422);
        }

        $spesialis->delete();

        return response()->json([
            'status' => true,
            'message' => 'Spesialis berhasil dihapus'
        ]);
    }
}
