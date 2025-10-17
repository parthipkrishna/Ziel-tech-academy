<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('short_description')->nullable();
            $table->text('full_description')->nullable();
            $table->text('target_audience')->nullable();
            $table->json('languages')->nullable();
            $table->boolean('status')->default(true);
            // Fee-related columns
            $table->decimal('course_fee', 10, 2)->nullable();
            $table->decimal('toolkit_fee', 10, 2)->nullable(); 
            // Cover images (keeping only one set for consistency)
            $table->string('cover_image_web')->nullable();
            $table->string('cover_image_mobile')->nullable();
            $table->integer('total_hours')->nullable();
            $table->json('tags')->nullable();
            $table->enum('type', ['web', 'lms'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
