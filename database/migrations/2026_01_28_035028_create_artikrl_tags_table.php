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
        Schema::create('artikel_tag', function (Blueprint $table) {
        $table->id();

        $table->foreignId('artikel_id')
            ->constrained('artikels')
            ->cascadeOnDelete();

        $table->foreignId('tag_id')
            ->constrained('tags')
            ->cascadeOnDelete();

        $table->unique(['artikel_id', 'tag_id']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikrl_tags');
    }
};
