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
     Schema::create('movies', function (Blueprint $table) {
    $table->id();
    $table->string('title')->index(); // Index for faster search
    $table->string('slug')->unique(); // For URLs: /watch/the-dark-knight
    $table->text('description');

    // Media Assets
    $table->string('thumbnail_url');       // Poster
    $table->string('video_url')->nullable(); // Only for single Movies
    $table->string('trailer_url')->nullable();

    // Metadata
    $table->integer('release_year');
    $table->string('rating'); // e.g., "PG-13", "TV-MA"
    $table->enum('type', ['movie', 'series'])->default('movie');
    $table->integer('views')->default(0); // To sort by "Popular"
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
