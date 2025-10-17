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
        Schema::create('question_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id'); // Foreign key for the question
            $table->string('answer_text'); // The answer text
            $table->string('answer_image')->nullable();// Image path (optional)
            $table->boolean('is_correct')->default(false); // Mark the answer as correct or not
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('question_id')->references('id')->on('exam_questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_answers');
    }
};
