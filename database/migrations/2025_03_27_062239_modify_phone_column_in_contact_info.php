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
        Schema::table('contact_info', function (Blueprint $table) {
            $table->string('phone', 55)->nullable()->change(); // Change length to 30
        });
    }

    public function down(): void
    {
        Schema::table('contact_info', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->change(); // Revert back to 20 if needed
        });
    }
};
