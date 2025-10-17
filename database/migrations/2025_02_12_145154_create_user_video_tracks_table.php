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
       Schema::create('user_video_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->enum('video_status', ['in_progress', 'completed', 'paused', 'locked']); // Video status
            $table->timestamp('last_watched_at')->nullable(); // Last watched timestamp
            $table->integer('seek_position')->nullable(); // Last seek position in seconds or percentage
            $table->timestamp('paused_at')->nullable(); // Timestamp of when the video was paused (if applicable)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_video_tracks');
    }
};
