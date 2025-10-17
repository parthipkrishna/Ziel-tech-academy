<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qc_feedback_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('qc_user_id');
            $table->unsignedBigInteger('student_feedback_id');

            $table->enum('action', [
                'initiated', 
                'meeting_scheduled', 
                'meeting_confirmed', 
                'feedback_drafted', 
                'feedback_submitted', 
                'feedback_approved', 
                'feedback_rejected'
            ]);

            $table->timestamp('action_at');

            $table->timestamps();

            // Indexes
            $table->index('qc_user_id');
            $table->index('student_feedback_id');
            $table->index('action');

            // Foreign Keys
            $table->foreign('qc_user_id')->references('id')->on('qcs')->onDelete('cascade');
            $table->foreign('student_feedback_id')->references('id')->on('student_feedbacks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qc_feedback_logs');
    }
};
