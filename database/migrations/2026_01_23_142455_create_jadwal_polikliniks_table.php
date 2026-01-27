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
        Schema::create('jadwal_polikliniks', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique();
            
            $table->string('anwar')->nullable();
            $table->string('khayati')->nullable();
            $table->string('haryono')->nullable();

            $table->string('ricky')->nullable();
            $table->string('adi')->nullable();

            $table->string('saria')->nullable();
            $table->string('jalul')->nullable();

            $table->string('inet')->nullable();

            $table->string('levi')->nullable();
            $table->string('alam')->nullable();

            $table->string('windy')->nullable();
            $table->string('yayan')->nullable();

            $table->string('vida')->nullable();
            $table->string('iwan')->nullable();

            $table->string('khalifa')->nullable();
            $table->string('tri')->nullable();

            $table->string('sarijan')->nullable();

            $table->string('inkoni')->nullable();

            $table->string('aziz')->nullable();

            $table->string('andreas')->nullable();

            $table->string('satya')->nullable();

            $table->string('andi')->nullable();

            $table->string('fisio')->nullable();

            $table->string('wicara')->nullable();

            $table->string('vaksinasi')->nullable();

            $table->string('desi')->nullable();
            $table->string('gizi')->nullable();

            $table->string('d1')->nullable();
            $table->string('d2')->nullable();
            $table->string('d3')->nullable();
            $table->string('d4')->nullable();
            $table->string('d5')->nullable();


            $table->boolean('status')->default(1); 
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_polikliniks');
    }
};
