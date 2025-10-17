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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('body');
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->enum('type', ['local', 'push'])->default('push');
            $table->enum('category_type', ['student', 'batch', 'general'])->default('general');
            $table->string('extra_info')->nullable();
            $table->json('student_ids')->nullable();
            $table->json('batch_ids')->nullable();
            $table->enum('status', ['Processing', 'Delivered', 'Failed'])->default('Processing');
            $table->string('delivered_count')->nullable();
            // Indexes
            $table->index('user_id');
            $table->index('type');
            $table->index(['user_id', 'status']);
            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
