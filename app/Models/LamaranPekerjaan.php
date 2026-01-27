<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LamaranPekerjaan extends Model
{
    use HasFactory;

     protected $table = 'lamaran_pekerjaan';

    protected $fillable = [
        'job_id',
        'nama_lengkap',
        'nik',
        'email',
        'no_hp',
        'tanggal_lahir',
        'alamat',
        'tinggi_badan',
        'berat_badan',
        'pendidikan',
        'asal_universitas',
        'jurusan',
        'pas_foto',
        'berkas_lamaran',
    ];

     public function job()
    {
        return $this->belongsTo(JobVacancy::class, 'job_id');
    }
}
