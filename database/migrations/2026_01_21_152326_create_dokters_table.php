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
        Schema::create('dokters', function (Blueprint $table) {
             $table->id();
            $table->string('nama');
            $table->string('title');
            $table->foreignId('spesialis_id')->constrained('spesialis')->cascadeOnDelete();
            $table->string('foto')->nullable();
            $table->boolean('poliklinik')->default(1); 
            $table->boolean('status')->default(1); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokters');
    }
};
