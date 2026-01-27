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
        Schema::create('vidios', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');

            $table->text('link'); // youtube / embed
            $table->string('thumbnail')->nullable();

            $table->boolean('featured')->default(0);
            $table->boolean('status')->default(1);

            $table->timestamps();

            $table->index(['featured', 'status']);
            $table->index('category');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vidios');
    }
};
