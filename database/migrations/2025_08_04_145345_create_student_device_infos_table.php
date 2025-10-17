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
        Schema::create('student_device_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('device_id')->nullable(); // Unique device identifier
            $table->string('device_type')->nullable(); // mobile, desktop, etc.
            $table->string('device_name')->nullable(); // e.g., iPhone 13
            $table->string('ip_address')->nullable();
            $table->string('browser')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->index(['student_id', 'device_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_device_infos');
    }
};
