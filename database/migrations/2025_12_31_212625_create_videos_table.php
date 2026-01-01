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
     Schema::create('videos', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('original_filename')->nullable();
        $table->string('stream_path')->nullable(); // Path to .m3u8 on S3
        $table->boolean('processed')->default(false);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
