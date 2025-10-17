<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id'); // live_exam_sessions reference
            $table->unsignedBigInteger('question_id'); // exam_questions reference
            $table->unsignedBigInteger('student_id'); // users reference
            $table->unsignedBigInteger('selected_answer_id')->index(); // question_answers reference
            $table->string('exam_attempt_id')->index();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('exam_questions')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('selected_answer_id')->references('id')->on('question_answers')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('student_answers');
    }
};
