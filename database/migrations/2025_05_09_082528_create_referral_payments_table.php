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
        Schema::create('referral_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('influencer_id')->index(); // FK to influencers

            $table->decimal('current_withdrawal', 10, 2); // Total payout amount
            $table->string('gst_number')->nullable(); // GST ID if applicable
            $table->date('payment_date'); // Date payment is made
            $table->enum('method', ['cash', 'upi', 'bank_transfer', 'cheque', 'other'])->default('other')->index();
            $table->string('transaction_id')->nullable()->index(); // UPI ref / bank txn / cheque no.
            $table->string('attachment_path')->nullable(); // Receipt file, image, etc. (stored in storage or S3)

            $table->enum('status', [
                'initiated',         // Payment process started
                'processing',        // In queue / being reviewed
                'completed',         // Paid successfully
                'failed',            // Payment failed (bank/UPI/etc)
                'rejected',          // Manually rejected
                'check_issues',      // Flagged for review (discrepancy found)
                'on_hold'            // Temporarily paused (e.g., document missing)
            ])->default('initiated')->index();
            $table->text('notes')->nullable(); // Optional admin comments
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('influencer_id')->references('id')->on('influencers')->onDelete('cascade');
        });     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_payments');
    }
};
