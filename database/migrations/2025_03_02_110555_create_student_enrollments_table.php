<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_enrollments', function (Blueprint $table) {
            $table->id(); // BIGINT Primary Key, Auto-increment
            $table->unsignedBigInteger('student_id'); // Foreign Key (users)
            $table->unsignedBigInteger('course_id'); // Foreign Key (courses)
            $table->enum('status', ['active', 'cancelled', 'enrolled', 'completed','free'])->default('enrolled');
            $table->timestamps(); // created_at and updated_at

            // Foreign Key Constraints
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_enrollments');
    }
};
