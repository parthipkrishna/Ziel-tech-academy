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
        Schema::table('exams', function (Blueprint $table) {
            // Duration in minutes (you can also use smallInteger if you want to restrict range)
            $table->integer('duration')->nullable()->after('status');

            // Total marks available
            $table->integer('total_marks')->nullable()->after('duration');

            // Minimum marks required to pass
            $table->integer('minimum_passing_marks')->nullable()->after('total_marks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['duration', 'total_marks', 'minimum_passing_marks']);
        });
    }
};
