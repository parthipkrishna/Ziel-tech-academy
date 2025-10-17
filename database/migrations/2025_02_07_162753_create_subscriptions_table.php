<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            // Manual foreign keys
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id');

            // Subscription dates
            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->enum('status', ['active', 'expired', 'cancelled'])
                  ->default('active')
                  ->index();

            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('student_id');
            $table->index('course_id');

            // Foreign key constraints
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
