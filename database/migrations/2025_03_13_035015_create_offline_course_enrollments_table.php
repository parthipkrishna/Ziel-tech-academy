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
        Schema::create('offline_course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offline_course_id')->constrained('offline_courses')->onDelete('cascade');
            $table->foreignId('offline_course_type_id')->constrained('offline_course_types')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('offline_students')->onDelete('cascade');
            $table->enum('status', ['active', 'cancelled', 'enrolled', 'completed'])->default('enrolled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offline_course_enrollments');
    }
};
