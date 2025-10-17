<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoProgressTable extends Migration
{
    public function up()
    {
        Schema::create('video_progress', function (Blueprint $table) {
            $table->id();

            // Manual foreign keys
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('video_id')->index();

            $table->integer('total_watch_time')->default(0); // in seconds
            $table->boolean('is_completed')->default(false);

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('student_id')->references('id')->on('students')->onDelete('restrict');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('restrict');

            // Prevent duplicate progress rows
            $table->unique(['student_id', 'video_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('video_progress');
    }
}
