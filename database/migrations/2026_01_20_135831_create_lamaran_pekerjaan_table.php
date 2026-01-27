<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lamaran_pekerjaan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('job_id')
                ->constrained('job_vacancies')
                ->cascadeOnDelete();

            $table->string('nama_lengkap');
            $table->string('nik', 16);
            $table->string('email');
            $table->string('no_hp');
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();

            $table->integer('tinggi_badan')->nullable();
            $table->integer('berat_badan')->nullable();

            $table->enum('pendidikan', ['SMA/SMK','D3','D4','S1','S2']);
            $table->string('asal_universitas')->nullable();
            $table->string('jurusan')->nullable();

            $table->string('pas_foto');
            $table->string('berkas_lamaran');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lamaran_pekerjaan');
    }
};
