<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_gateway_configs', function (Blueprint $table) {
            $table->id();

            $table->string('gateway_name')->unique(); // e.g., razorpay, stripe
            $table->string('display_name')->nullable(); // For UI display

            $table->enum('status', ['active', 'inactive'])->default('inactive')->index();

            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('webhook_secret')->nullable();
            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateway_configs');
    }
};