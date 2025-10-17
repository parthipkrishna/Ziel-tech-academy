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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('batch_id');
            $table->foreignId('subject_session_id')->nullable()->constrained()->onDelete('cascade');

            $table->string('name');
            $table->enum('type', [ 'Assessment', 'Exam'])->default('Exam');
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['Scheduled', 'Ongoing', 'Completed'])->default('Scheduled');
            $table->timestamps();
            
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
