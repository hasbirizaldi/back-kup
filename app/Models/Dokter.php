<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;
    protected $table = 'dokters';

    protected $fillable = [
        'nama',
        'title',
        'spesialis_id',
        'foto',
        'poliklinik',
        'status'
    ];

    protected $casts = [
        'poliklinik' => 'boolean',
        'status' => 'boolean'
    ];

    public function spesialis()
    {
        return $this->belongsTo(Spesialis::class);
    }

    public function jadwal()
    {
        return $this->hasMany(JadwalDokter::class);
    }

    public function jadwalPoliklinik()
    {
        return $this->hasMany(JadwalPoliklinik::class);
    }

  

}
