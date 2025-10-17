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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name'); // Module name
            $table->text('short_desc'); // Short description
            $table->text('desc'); // Full description
            $table->boolean('status')->default(true); // Boolean status (active/inactive)
            $table->unsignedBigInteger('course_id'); // Foreign key to the courses table
            $table->integer('total_hours'); // Total hours for the module
            $table->string('mobile_thumbnail')->nullable(); // Mobile-specific thumbnail
            $table->string('web_thumbnail')->nullable(); // Web-specific thumbnail
            $table->timestamps(); // Created at and updated at timestamps
        
            // Foreign key constraint
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        
            // Indexing for performance
            $table->index('course_id');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
