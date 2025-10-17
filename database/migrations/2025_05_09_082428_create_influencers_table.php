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
        Schema::create('influencers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable()->index();
            $table->string('image')->nullable();  // For storing the image URL or file path
            $table->string('kyc_docs')->nullable();  // For storing KYC documents (could be URLs or file paths)    
            $table->unsignedBigInteger('referral_code_id')->index();
            $table->decimal('commission_per_user', 10, 2)->default(0);
            $table->timestamps();
        
            $table->foreign('referral_code_id')->references('id')->on('referral_codes')->onDelete('cascade');
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('influencers');
    }
};
