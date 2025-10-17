<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('razorpay_orders', function (Blueprint $table) {
            $table->id();

            // Manual foreign key to payments
            $table->unsignedBigInteger('payment_id');

            $table->string('razorpay_order_id')->index();
            $table->string('razorpay_payment_id')->nullable()->index();
            $table->string('currency', 10)->nullable(); // e.g. INR, USD
            $table->string('signature')->nullable();
            $table->string('receipt')->nullable();
            $table->json('response_payload')->nullable();
            $table->json('error_payload')->nullable();

            $table->timestamps();

            // Index
            $table->index('payment_id');

            // Foreign Key Constraint
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('razorpay_orders');
    }
};
