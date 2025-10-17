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
        Schema::create('contact_info', function (Blueprint $table) {
            $table->id(); // BIGINT Primary Key, Auto-increment
            $table->string('phone', 20)->nullable(); // Primary contact number
            $table->string('email', 255)->notNullable(); // Primary email address
            $table->text('address')->notNullable(); // Headquarters address
            $table->string('google_map_link', 255)->nullable(); // Google Maps link
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_info');
    }
};
