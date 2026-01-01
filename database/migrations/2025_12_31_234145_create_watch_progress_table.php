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
       Schema::create('watch_progress', function (Blueprint $table) {
    $table->id();
    $table->foreignId('profile_id')->constrained()->onDelete('cascade');
    $table->foreignId('movie_id')->constrained()->onDelete('cascade'); // The show/movie

    // If it's a series, we need to know specifically which episode they are on
    $table->foreignId('episode_id')->nullable()->constrained()->onDelete('cascade');

    $table->integer('progress_seconds')->default(0); // Where they stopped
    $table->boolean('is_finished')->default(false);
    $table->timestamp('last_watched_at')->useCurrent();

    // Ensure one record per profile per movie
    $table->unique(['profile_id', 'movie_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watch_progress');
    }
};
