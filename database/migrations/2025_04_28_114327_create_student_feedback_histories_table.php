<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_feedback_histories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_feedback_id');
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('qc_user_id');
            $table->text('student_summary');
            $table->text('qc_feedback_summary');
            $table->tinyInteger('video_rating')->unsigned();
            $table->tinyInteger('practical_rating')->unsigned();
            $table->tinyInteger('understanding_rating')->unsigned();
            $table->enum('status', ['draft','approved', 'rejected'])->default('draft');
            $table->timestamps();

            // Indexes
            $table->index('student_feedback_id');
            $table->index('module_id');
            $table->index('qc_user_id');
            $table->index('status');

            // Foreign Keys
            $table->foreign('student_feedback_id')->references('id')->on('student_feedbacks')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_feedback_histories');
    }
};
