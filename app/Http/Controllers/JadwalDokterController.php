<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use Illuminate\Http\Request;

class JadwalDokterController extends Controller
{
   public function index(Request $request)
    {
        $query = JadwalDokter::with('dokter');

        // 🔍 Filter NAMA DOKTER
        if ($request->filled('nama')) {
            $query->whereHas('dokter', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->nama . '%');
            });
        }

        // 📅 Filter HARI
        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        // 📄 PAGINATION
        $perPage = $request->get('per_page', 10); // default 10 data per halaman

        $jadwals = $query
            ->orderBy('hari')
            ->paginate($perPage);

        return response()->json($jadwals);
    }

    /**
     * POST /api/jadwal-dokters
     */
    public function store(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'hari'      => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jam'       => 'nullable|string|max:50',
            'status'    => 'boolean',
        ]);

        $jadwal = JadwalDokter::create([
            'dokter_id' => $request->dokter_id,
            'hari'      => $request->hari,
            'jam'       => $request->jam,
            'status'    => $request->status ?? 1,
        ]);

        return response()->json([
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $jadwal,
        ], 201);
    }

    /**
     * GET /api/jadwal-dokters/{id}
     */
    public function show($id)
    {
        $jadwal = JadwalDokter::with('dokter')->findOrFail($id);

        return response()->json($jadwal);
    }

    /**
     * PUT /api/jadwal-dokters/{id}
     */
    public function update(Request $request, $id)
    {
        $jadwal = JadwalDokter::findOrFail($id);

        $request->validate([
            'dokter_id' => 'required|exists:dokters,id',
            'hari'      => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jam'       => 'nullable|string|max:50',
            'status'    => 'boolean',
        ]);

        $jadwal->update([
            'dokter_id' => $request->dokter_id,
            'hari'      => $request->hari,
            'jam'       => $request->jam,
            'status'    => $request->status,
        ]);

        return response()->json([
            'message' => 'Jadwal berhasil diupdate',
            'data' => $jadwal,
        ]);
    }

    /**
     * DELETE /api/jadwal-dokters/{id}
     */
    public function destroy($id)
    {
        $jadwal = JadwalDokter::findOrFail($id);
        $jadwal->delete();

        return response()->json([
            'message' => 'Jadwal berhasil dihapus',
        ]);
    }


    // ////////////////PUBLIC ROUTE//////////////////
    public function indexPublic(Request $request)
    {
        $dokters = Dokter::with(['spesialis', 'jadwal'])
            ->where('status', 1)
            ->get()
            ->map(function ($dokter) {

                // Default jadwal (biar tabel React rapi)
                $jadwal = [
                    'senin' => '-',
                    'selasa' => '-',
                    'rabu' => '-',
                    'kamis' => '-',
                    'jumat' => '-',
                    'sabtu' => '-',
                    'minggu' => '-',
                ];

                // Isi jadwal dari database
                foreach ($dokter->jadwal->where('status', 1) as $j) {
                    $jadwal[$j->hari] = $j->jam ?? '-';
                }

                return [
                    'id' => $dokter->id,
                    'nama' => $dokter->nama,
                    'spesialis' => $dokter->spesialis->nama,
                    'foto' => $dokter->foto 
                        ? asset('storage/' . $dokter->foto) 
                        : asset('default-dokter.png'),
                    'jadwal' => $jadwal
                ];
            });

        return response()->json($dokters);
    }


}
