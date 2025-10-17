<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'toolkit_id')) {
                $table->unsignedBigInteger('toolkit_id')->nullable()->after('course_id');

                // Add foreign key (assuming toolkits.id is bigIncrements)
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'toolkit_id')) {
                $table->dropColumn('toolkit_id');
            }
        });
    }
};
