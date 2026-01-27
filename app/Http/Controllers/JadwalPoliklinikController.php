<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\JadwalPoliklinik;

class JadwalPoliklinikController extends Controller
{
     protected $casts = [
        'tanggal' => 'date:Y-m-d',
    ];

    /* =====================
     * GET ALL DATA
     * ===================== */
    public function index()
    {
        $data = JadwalPoliklinik::orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    ...$item->toArray(),
                    'tanggal' => $item->tanggal?->format('Y-m-d'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    /* =====================
     * STORE (CREATE)
     * ===================== */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|unique:jadwal_polikliniks,tanggal',
        ]);

        $data = JadwalPoliklinik::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    /* =====================
     * SHOW BY ID
     * ===================== */
    public function show($id)
    {
        $data = JadwalPoliklinik::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /* =====================
     * UPDATE
     * ===================== */
    public function update(Request $request, $id)
    {
        $jadwal = JadwalPoliklinik::findOrFail($id);

        $request->validate([
            'tanggal' => [
                'required',
                'date',
                Rule::unique('jadwal_polikliniks', 'tanggal')->ignore($jadwal->id),
            ],
        ]);

        $jadwal->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diperbarui',
            'data' => $jadwal
        ]);
    }

    /* =====================
     * DELETE
     * ===================== */
    public function destroy($id)
    {
        $jadwal = JadwalPoliklinik::findOrFail($id);
        $jadwal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus'
        ]);
    }

    // ////////////////ROUTE PUBLIC ///////////////////////
    public function indexPublic(Request $request)
    {
        // pastikan Carbon pakai WIB
        $tanggal = $request->query('tanggal');

        $date = $tanggal
            ? Carbon::createFromFormat('Y-m-d', $tanggal, 'Asia/Jakarta')
            : Carbon::now('Asia/Jakarta')->startOfDay();

        $data = JadwalPoliklinik::whereDate('tanggal', $date->toDateString())
            ->where('status', 1)
            ->get();

        return response()->json([
            'status'  => true,
            'tanggal' => $date->toDateString(),
            'data'    => $data
        ]);
    }



}
