<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_feedback_sessions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_feedback_id');
            $table->string('qc_user_id');
            $table->timestamp('scheduled_at');
            $table->string('meeting_link');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('student_feedback_id');
            $table->index('qc_user_id');
            $table->index('status');

            // Foreign Keys
            $table->foreign('student_feedback_id')->references('id')->on('student_feedbacks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_feedback_sessions');
    }
};
