<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('exam_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id'); // Manually define the foreign key column for live_exam_sessions
            $table->unsignedBigInteger('student_id'); // Manually define the foreign key column for users
            $table->string('exam_attempt_id')->index();
            $table->enum('status', ['Join', 'Left', 'Completed'])->default('Join'); // Participant status
            $table->timestamps();

              // Track event timestamps
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Manually add foreign key constraints
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('exam_participants');
    }
};
