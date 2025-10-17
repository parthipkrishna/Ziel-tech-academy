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
        Schema::create('quick_links', function (Blueprint $table) {
            $table->id(); // BIGINT Primary Key, Auto-increment
            $table->string('title', 255)->notNullable(); // Link title
            $table->string('url', 255)->notNullable(); // Navigation URL
            $table->integer('order')->default(0); // Sorting priority
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_links');
    }
};
