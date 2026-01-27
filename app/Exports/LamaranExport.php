<?php

namespace App\Exports;

use App\Models\LamaranPekerjaan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LamaranExport implements FromCollection, WithHeadings
{
    protected $posisi;

    public function __construct($posisi = null)
    {
        $this->posisi = $posisi;
    }

    public function collection(): Collection
    {
        $query = LamaranPekerjaan::with('job');

        if ($this->posisi) {
            $query->whereHas('job', function ($q) {
                $q->where('description', 'like', '%' . $this->posisi . '%');
            });
        }

        return $query->latest()->get()->map(function ($item) {
            return [
                'Nama' => $item->nama_lengkap,
                'Posisi' => $item->job?->description,
                'Pendidikan' => $item->pendidikan,
                'Universitas' => $item->asal_universitas,
                'Jurusan' => $item->jurusan,
                'No HP' => $item->no_hp,
                 'Tanggal Lahir' => $item->tanggal_lahir
                ? \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y')
                : '-',
                'Usia' => now()->diffInYears($item->tanggal_lahir),
                'Tinggi Badan' => $item->tinggi_badan,
                'Berat Badan' => $item->berat_badan,
                'Alamat' => $item->alamat,
                'Tanggal Lamar' => $item->created_at->format('d-m-Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Posisi',
            'Pendidikan',
            'Universitas',
            'Jurusan',
            'No HP',
             'Tanggal Lahir',
            'Usia',
            'Tinggi Badan',
            'Berat Badan',
            'Alamat',
            'Tanggal Lamar',
        ];
    }
}
