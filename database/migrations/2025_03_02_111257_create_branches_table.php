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
        Schema::create('branches', function (Blueprint $table) {
            $table->id(); // BIGINT Primary Key, Auto-increment
            $table->string('name', 255)->notNullable(); // Branch name
            $table->text('address')->notNullable(); // Full address
            $table->unsignedBigInteger('campus_id')->nullable(); // Foreign Key (campuses)
            $table->string('contact_number', 20)->nullable(); // Contact number
            $table->string('image', 255)->nullable(); // Branch image
            $table->string('google_map_link', 255)->nullable(); // Google Maps link
            $table->boolean('status')->default(true); 
            $table->timestamps(); // created_at and updated_at

            // Foreign Key Constraint
            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
