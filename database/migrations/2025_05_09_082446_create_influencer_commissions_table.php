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
        Schema::create('influencer_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('influencer_id')->index();
            $table->unsignedBigInteger('referral_use_id')->index();
            $table->decimal('amount', 10, 2)->index();
            $table->enum('status', ['pending', 'paid'])->default('pending')->index();
            $table->timestamp('paid_at')->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        
            $table->foreign('influencer_id')->references('id')->on('influencers')->onDelete('cascade');
            $table->foreign('referral_use_id')->references('id')->on('referral_uses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('influencer_commissions');
    }
};
