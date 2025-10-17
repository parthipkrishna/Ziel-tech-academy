<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qcs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->json('batch_ids')->nullable();  // Storing array of batch IDs
            $table->date('joined_date')->nullable();
            $table->string('age')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('qualifications')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qcs');
    }
};
