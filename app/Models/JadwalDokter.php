<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDokter extends Model
{
    use HasFactory;
    protected $table = 'jadwal_dokters';
    protected $fillable = [
        'dokter_id',
        'hari',
        'jam',
        'status',
    ];

    // Relasi ke Dokter
    public function dokter()
    {
        return $this->belongsTo(Dokter::class);
    }
}
