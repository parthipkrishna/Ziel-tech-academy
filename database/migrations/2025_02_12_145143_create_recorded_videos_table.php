<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{    public function up()
    {
        Schema::create('recorded_videos', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('subject_id')->constrained()->onDelete('cascade'); // Foreign Key to subjects
            $table->foreignId('subject_session_id')->constrained()->onDelete('cascade'); // Foreign Key to sessions
            $table->foreignId('video_id')->constrained()->onDelete('cascade'); // Foreign Key to videos
            $table->boolean('is_enabled')->default(true); // Status Flag (Enabled/Disabled)
            $table->integer('video_order')->default(0); // Order for sorting videos within a session (integer)
            $table->timestamps(); // Created At & Updated At
            
            // Index on subject_id for faster querying
            $table->index('subject_id'); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('recorded_videos');
    }
};
