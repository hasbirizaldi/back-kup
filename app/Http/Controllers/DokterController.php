<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokterController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokter::with('spesialis');

        // Filter berdasarkan nama (partial match)
        if ($request->nama) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Filter berdasarkan spesialis (nama spesialis, join tabel spesialis)
        if ($request->spesialis) {
            $query->whereHas('spesialis', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->spesialis . '%');
            });
        }

        // Filter berdasarkan spesialis_id (jika ada)
        if ($request->spesialis_id) {
            $query->where('spesialis_id', $request->spesialis_id);
        }

        // Filter status
        if ($request->status !== null) {
            $query->where('status', $request->status);
        }

        // Filter poliklinik
        if ($request->poliklinik !== null) {
            $query->where('poliklinik', $request->poliklinik);
        }

        $dokters = $query->orderBy('nama')->paginate(10);

        return response()->json($dokters);
    }

   public function getAllDokter()
    {
        return response()->json(
            Dokter::with('spesialis')
                ->where('status', 1)
                ->orderBy('nama')
                ->get()
        );
    }



    /**
     * POST /api/dokters
     */
    public function store(Request $request)
    {
        $request->merge([
            'poliklinik' => (int) $request->poliklinik,
            'status' => (int) $request->status,
        ]);

        $request->validate([
            'nama' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'spesialis_id' => 'required|exists:spesialis,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'poliklinik' => 'required|in:0,1',
            'status' => 'required|in:0,1',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('dokter', 'public');
        }

        $dokter = Dokter::create([
            'nama' => $request->nama,
            'title' => $request->title,
            'spesialis_id' => $request->spesialis_id,
            'foto' => $fotoPath,
            'poliklinik' => $request->poliklinik,
            'status' => $request->status
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Dokter berhasil ditambahkan',
            'data' => $dokter
        ], 201);
    }

    /**
     * GET /api/dokters/{id}
     */
    public function show($id)
    {
        $dokter = Dokter::with('spesialis')->find($id);

        if (!$dokter) {
            return response()->json([
                'status' => false,
                'message' => 'Dokter tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $dokter
        ]);
    }

    /**
     * PUT /api/dokters/{id}
     */
    public function update(Request $request, $id)
    {
        $dokter = Dokter::find($id);

        if (!$dokter) {
            return response()->json([
                'status' => false,
                'message' => 'Dokter tidak ditemukan'
            ], 404);
        }

        $request->merge([
            'poliklinik' => (int) $request->poliklinik,
            'status' => (int) $request->status,
        ]);

        $request->validate([
            'nama' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'spesialis_id' => 'required|exists:spesialis,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'poliklinik' => 'required|in:0,1',
            'status' => 'required|in:0,1',
        ]);

        if ($request->hasFile('foto')) {
            if ($dokter->foto) {
                Storage::disk('public')->delete($dokter->foto);
            }

            $dokter->foto = $request->file('foto')
                ->store('dokter', 'public');
        }

        $dokter->update([
            'nama' => $request->nama,
            'title' => $request->title,
            'spesialis_id' => $request->spesialis_id,
            'poliklinik' => $request->poliklinik,
            'status' => $request->status
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Dokter berhasil diperbarui',
            'data' => $dokter
        ]);
    }

    /**
     * DELETE /api/dokters/{id}
     */
    public function destroy($id)
    {
        $dokter = Dokter::find($id);

        if (!$dokter) {
            return response()->json([
                'status' => false,
                'message' => 'Dokter tidak ditemukan'
            ], 404);
        }

        if ($dokter->foto) {
            Storage::disk('public')->delete($dokter->foto);
        }

        $dokter->delete();

        return response()->json([
            'status' => true,
            'message' => 'Dokter berhasil dihapus'
        ]);
    }
}
