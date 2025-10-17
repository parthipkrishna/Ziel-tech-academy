<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Manual foreign keys
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id')->index()->nullable();

            // Payment detailss
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('INR');

            $table->enum('payment_gateway', ['razorpay', 'manual','gpay','bank_transfer','cash'])
                  ->default('razorpay')
                  ->index();

            $table->enum('status', ['initiated', 'success', 'pending', 'failed', 'cancelled'])
                  ->default('initiated')
                  ->index();

            $table->string('transaction_id')->nullable()->unique();

            $table->timestamp('paid_at')->nullable();

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
        Schema::dropIfExists('payments');
    }
};