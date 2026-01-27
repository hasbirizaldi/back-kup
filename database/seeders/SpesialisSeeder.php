<?php

namespace Database\Seeders;

use App\Models\Spesialis;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SpesialisSeeder extends Seeder
{
    public function run()
    {
        $list = [
            'Spesialis Penyakit Dalam',
            'Spesialis Bedah',
            'Spesialis Anak',
            'Spesialis Kandungan',
            'Spesialis THT',
            'Spesialis Radiologi',
            'Spesialis Saraf',
            'Spesialis Jantung',
            'Spesialis Mata',
            'Spesialis Orthopedi',
            'Spesialis Urologi',
            'Spesialis Kulit & Kelamin',
            'Spesialis Rehab Medik',
            'Patologi Klinik',
            'Spesialis Anestesi',
            'Dokter Umum',
        ];

        foreach ($list as $nama) {
            Spesialis::firstOrCreate(['nama' => $nama]);
        }
    }
}
