<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('student_limit');
            $table->string('batch_number')->unique(); // Unique batch identifier

            $table->unsignedBigInteger('tutor_id')->nullable(); // FK to users
            $table->unsignedBigInteger('batch_in_charge_id')->nullable(); // FK to users
            $table->unsignedBigInteger('course_id')->nullable(); // FK to courses

            $table->json('qc_ids')->nullable(); // Store multiple QC user IDs
            $table->boolean('status')->default(true); // true = active
            $table->boolean('is_full')->default(false); // Mark batch full
            $table->softDeletes();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('tutor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('batch_in_charge_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

            // Indexes
            $table->index('tutor_id');
            $table->index('batch_in_charge_id');
            $table->index('course_id');
            $table->index('batch_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
