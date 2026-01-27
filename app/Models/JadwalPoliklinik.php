<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPoliklinik extends Model
{
    use HasFactory;
    protected $table = 'jadwal_polikliniks';

    protected $fillable = [
        'tanggal',

        'anwar',
        'khayati',
        'haryono',

        'ricky',
        'adi',

        'saria',
        'jalul',

        'inet',

        'levi',
        'alam',

        'windy',
        'yayan',

        'vida',
        'iwan',

        'khalifa',
        'tri',

        'sarijan',

        'inkoni',

        'aziz',

        'andreas',

        'satya',

        'andi',

        'fisio',

        'wicara',

        'vaksinasi',

        'desi',
        'gizi',

        'd1',
        'd2',
        'd3',
        'd4',
        'd5',

        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status'  => 'boolean',
    ];
}
