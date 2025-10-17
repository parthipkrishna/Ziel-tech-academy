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
        Schema::create('batch_channels', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('batch_id'); // Reference to batches table
            $table->string('group_name');           // e.g., WhatsApp group name
            $table->string('admin_name');           // Group admin's name
            $table->string('admin_phone');          // Group admin's phone number
            $table->unsignedBigInteger('admin_id'); // Reference to user table
            $table->enum('type', ['whatsapp', 'telegram', 'other']);
            $table->boolean('status')->default(true); // true = active, false = inactive

            $table->timestamps();
            $table->softDeletes();

            // Manual foreign keys
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');

            // Indexes
            $table->index('batch_id');
            $table->index('admin_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_channels');
    }
};
