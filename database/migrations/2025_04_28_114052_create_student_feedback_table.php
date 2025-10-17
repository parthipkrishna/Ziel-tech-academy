<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_feedbacks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('module_id');
            $table->string('batch');
            $table->string('admission_number');
            $table->string('location')->nullable();
            $table->string('contact_number')->nullable();
            $table->enum('status', ['pending', 'initiated', 'scheduled', 'draft', 'completed', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            // Indexes
            $table->index('student_id');
            $table->index('status');

            // Foreign Key
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_feedbacks');
    }
};
