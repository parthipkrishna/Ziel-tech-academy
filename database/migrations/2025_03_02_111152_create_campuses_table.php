<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campuses', function (Blueprint $table) {
            $table->id(); // BIGINT Primary Key, Auto-increment
            $table->boolean('status')->default(true); // Default to true
            $table->text('home_tour')->nullable(); // Home tour (text)
            $table->string('home_tour_image')->nullable(); // Home tour image
            $table->text('desc')->nullable(); // Campus description
            $table->string('short')->nullable(); // Short description
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campuses');
    }
};
