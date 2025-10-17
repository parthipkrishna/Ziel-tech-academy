<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedInteger('min_loyalty_points')->default(0)->after('course_fee')->index();
            $table->unsignedInteger('loyalty_points_earn')->default(0)->after('min_loyalty_points')->index();
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('min_loyalty_points');
        });
    }
};
