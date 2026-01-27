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
        Schema::create('artikels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('category');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();

            $table->date('published_at')->nullable();

            $table->string('image');
            $table->string('image_alt')->nullable();

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();

            $table->longText('content');

            $table->integer('reading_time')->nullable();
            $table->boolean('noindex')->default(0);

            $table->boolean('status')->default(1);
            $table->boolean('featured')->default(0);

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikels');
    }
};
