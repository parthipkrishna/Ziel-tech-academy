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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id(); // BIGINT Primary Key, Auto-increment
            $table->string('name', 255)->unique()->notNullable(); // Permission slug (e.g., edit_courses)
            $table->string('display_name', 255)->notNullable(); // Human-readable name
            $table->text('description')->nullable(); // Permission purpose
            $table->enum('type', ['web', 'lms'])->nullable();
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
