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
        DB::statement("ALTER TABLE web_banners MODIFY type ENUM(
            'home', 'campus', 'promo', 'gallery', 'about us', 'placement', 'contact us', 'other', 'branches'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE web_banners MODIFY type ENUM(
            'home', 'compus', 'promo', 'gallery', 'about us', 'placement', 'contact us', 'other', 'branches'
        ) NOT NULL");
    }
};
