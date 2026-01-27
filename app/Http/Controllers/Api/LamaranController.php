<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Exports\LamaranExport;
use App\Models\LamaranPekerjaan;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class LamaranController extends Controller
{

    public function index(Request $request)
    {
        $query = LamaranPekerjaan::with('job')->latest();

        // filter posisi
        if ($request->filled('posisi')) {
            $query->whereHas('job', function ($q) use ($request) {
                $q->where('description', 'like', '%' . $request->posisi . '%');
            });
        }

        return response()->json(
            $query->paginate(10) // jumlah data per halaman
        );
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new LamaranExport($request->posisi),
            'data-lamaran.xlsx'
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_id'            => 'required|exists:job_vacancies,id',
            'nama_lengkap'      => 'required|string|max:255',
            'nik'               => 'required|digits:16|unique:lamaran_pekerjaan,nik',
            'email'             => 'required|email',
            'no_hp'             => 'required|string|max:20',
            'tanggal_lahir'     => 'nullable|date',
            'alamat'            => 'nullable|string',
            'tinggi_badan'      => 'nullable|integer',
            'berat_badan'       => 'nullable|integer',
            'pendidikan'        => 'required|in:SMA/SMK,D3,D4,S1,S2',
            'asal_universitas'  => 'nullable|string|max:255',
            'jurusan'           => 'nullable|string|max:255',

            'pas_foto'          => 'required|image|mimes:jpg,jpeg,png|max:512',
            'berkas_lamaran'    => 'required|mimes:pdf|max:2048',
        ]);

        // upload file
        $pasFotoPath = $request->file('pas_foto')
            ->store('lamaran/pas-foto', 'public');

        $berkasPath = $request->file('berkas_lamaran')
            ->store('lamaran/berkas', 'public');

        $lamaran = LamaranPekerjaan::create([
            ...$validated,
            'pas_foto' => $pasFotoPath,
            'berkas_lamaran' => $berkasPath,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Lamaran berhasil dikirim',
            'data' => $lamaran
        ], 201);
    }

    
    public function destroy($id)
    {
        $lamaran = LamaranPekerjaan::findOrFail($id);

        // hapus file
        if ($lamaran->pas_foto) {
            Storage::disk('public')->delete($lamaran->pas_foto);
        }

        if ($lamaran->berkas_lamaran) {
            Storage::disk('public')->delete($lamaran->berkas_lamaran);
        }

        $lamaran->delete();

        return response()->json([
            'status' => true,
            'message' => 'Lamaran berhasil dihapus'
        ]);
    }

    public function destroyAll()
    {
        $lamarans = LamaranPekerjaan::all();

        foreach ($lamarans as $lamaran) {
            if ($lamaran->pas_foto) {
                Storage::disk('public')->delete($lamaran->pas_foto);
            }

            if ($lamaran->berkas_lamaran) {
                Storage::disk('public')->delete($lamaran->berkas_lamaran);
            }
        }

        LamaranPekerjaan::truncate(); // hapus semua data

        return response()->json([
            'status' => true,
            'message' => 'Semua data lamaran berhasil dihapus'
        ]);
    }

    public function checkNik(Request $request)
    {
        $nik = $request->query('nik');
        if (!$nik || !preg_match('/^\d{16}$/', $nik)) {
            return response()->json(['error' => 'NIK tidak valid'], 400);
        }

        $exists = LamaranPekerjaan::where('nik', $nik)->exists();

        return response()->json(['exists' => $exists]);
    }

}
