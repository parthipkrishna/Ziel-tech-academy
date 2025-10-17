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
        Schema::table('branches', function (Blueprint $table) {
            $table->longText('google_map_link')->change(); // Change the column type to LONGTEXT
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->string('google_map_link', 255)->nullable()->change(); // Rollback to the original type
        });
    }
};
