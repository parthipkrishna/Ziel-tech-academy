<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('student_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');           // Reference to exams
            $table->unsignedBigInteger('student_id');        // Reference to students/users
            $table->string('exam_attempt_id')->index();
            $table->integer('total_score')->default(0);
            $table->integer('correct_answers')->default(0);
            $table->integer('incorrect_answers')->default(0);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            // Optional: make sure a student cannot have duplicate scores for the same attempt
            $table->unique(['exam_id', 'student_id', 'exam_attempt_id'], 'unique_score_per_attempt');
        });
    }

    public function down() {
        Schema::dropIfExists('student_scores');
    }
};
