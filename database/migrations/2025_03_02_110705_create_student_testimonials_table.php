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
        Schema::create('student_testimonials', function (Blueprint $table) {
            $table->id(); // BIGINT Primary Key, Auto-increment
            $table->unsignedBigInteger('student_id'); // Foreign Key (users)
            $table->text('content')->notNullable(); // Testimonial content
            $table->integer('rating')->notNullable(); // Star rating (1-5)
            $table->timestamps(); // created_at and updated_at

            // Foreign Key Constraint
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_testimonials');
    }
};
