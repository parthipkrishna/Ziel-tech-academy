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
        Schema::create('referral_uses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referral_code_id')->index();
            $table->unsignedBigInteger('used_by_user_id')->index();
            $table->timestamp('used_at')->nullable()->index();
            $table->string('source')->nullable()->index();
            $table->enum('status', ['processing', 'cancelled', 'onboarded'])->default('processing')->index();
            $table->timestamp('converted_at')->nullable()->index();
            $table->timestamps();
        
            $table->foreign('referral_code_id')->references('id')->on('referral_codes')->onDelete('cascade');
            $table->foreign('used_by_user_id')->references('id')->on('users');
        });    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_uses');
    }
};
