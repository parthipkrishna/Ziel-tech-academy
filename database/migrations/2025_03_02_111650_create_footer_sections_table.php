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
        Schema::create('footer_sections', function (Blueprint $table) {
            $table->id(); // BIGINT Primary Key, Auto-increment
            $table->string('title', 255)->Nullable(); // Section title
            $table->text('short_desc')->Nullable();
            $table->string('copy_right')->Nullable();
            $table->string('playstore')->Nullable();
            $table->string('appstore')->Nullable();
            $table->string('footer_logo')->Nullable();
            $table->string('slug', 255)->unique()->Nullable(); // Unique identifier (e.g., "privacy")
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_sections');
    }
};
