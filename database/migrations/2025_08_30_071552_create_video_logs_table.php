<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoLogsTable extends Migration
{
    public function up()
    {
        Schema::create('video_logs', function (Blueprint $table) {
            $table->id();

            // Manual foreign keys
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('subject_id')->index();
            $table->unsignedBigInteger('subject_session_id')->index();
            $table->unsignedBigInteger('video_id')->index();

            $table->integer('start_time')->default(0); // in seconds
            $table->integer('end_time')->nullable();   // in seconds
            $table->integer('duration')->default(0);   // seconds watched in this log
            $table->enum('status', ['watching', 'left', 'completed'])->default('watching');

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('student_id')
                ->references('id')->on('students')->onDelete('restrict'); // still cascade for student deletion
            $table->foreign('subject_id')
                ->references('id')->on('subjects')->onDelete('restrict'); // prevent automatic delete
            $table->foreign('subject_session_id')
                ->references('id')->on('subject_sessions')->onDelete('restrict'); // prevent automatic delete
            $table->foreign('video_id')
                ->references('id')->on('videos')->onDelete('cascade'); // optional: delete if video removed


            // Composite indexes for fast lookup
            $table->index(['student_id', 'video_id']);
            $table->index(['student_id', 'subject_session_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('video_logs');
    }
}
