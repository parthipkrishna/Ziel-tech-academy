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
        Schema::create('company_infos', function (Blueprint $table) {
            $table->id(); // BIGINT Primary Key, Auto-increment
            $table->text('mission')->nullable(); // Company's mission statement
            $table->text('vision')->nullable(); // Company's vision
            $table->text('why_choose_us')->nullable(); // Reasons to choose the academy
            $table->text('offerings')->nullable(); // List of services and offerings
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_infos');
    }
};
