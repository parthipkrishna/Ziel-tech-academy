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
        Schema::create('offline_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('offline_courses')->onDelete('cascade');
            $table->string('name'); // Module name
            $table->text('short_desc'); // Short description
            $table->text('desc'); // Full description
            $table->boolean('status')->default(true); // Boolean status (active/inactive)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offline_subjects');
    }
};
