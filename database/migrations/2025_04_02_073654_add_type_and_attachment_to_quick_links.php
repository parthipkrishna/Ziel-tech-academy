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
        Schema::table('quick_links', function (Blueprint $table) {
            $table->enum('type', ['LINK', 'ATTACHMENT'])->nullable()->after('url'); 
            $table->string('attachment', 255)->nullable()->after('type'); 
        }); 
    }

    public function down(): void
    {
        Schema::table('quick_links', function (Blueprint $table) {
            $table->dropColumn(['type', 'attachment']);
        });
    }
};