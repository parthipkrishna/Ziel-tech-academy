<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['name', 'display_name', 'description']);

            // Add new columns
            $table->string('section_name')->after('id');
            $table->string('permission_name')->after('section_name');
            $table->boolean('status')->default(true)->after('permission_name');
        });
    }


    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            // Add old columns back
            $table->string('name', 255)->unique()->notNullable();
            $table->string('display_name', 255)->notNullable();
            $table->text('description')->nullable();

            // Drop new columns
            $table->dropColumn(['section_name', 'permission_name', 'status']);
        });
    }
};
