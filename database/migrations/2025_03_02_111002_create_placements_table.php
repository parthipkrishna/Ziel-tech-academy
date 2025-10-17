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
        Schema::create('placements', function (Blueprint $table) {
            $table->id(); // BIGINT Primary Key, Auto-increment
            $table->string('company_name', 255)->notNullable(); // Placement partner name
            $table->text('description')->nullable(); // Partner details
            $table->text('opportunities')->nullable(); // Job opportunities offered
            $table->string('website', 255)->nullable(); // Partner website URL
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placements');
    }
};
