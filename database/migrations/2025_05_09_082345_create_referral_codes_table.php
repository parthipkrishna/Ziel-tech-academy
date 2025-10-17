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
        Schema::create('referral_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->index();
            $table->unsignedBigInteger('generated_by')->index(); // Index added
            $table->enum('type', ['student', 'influencer'])->index();
            $table->text('deeplink_url')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        
            $table->foreign('generated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_codes');
    }
};
