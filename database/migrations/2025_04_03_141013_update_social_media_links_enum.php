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
        DB::statement("ALTER TABLE social_media_links MODIFY COLUMN platform ENUM('facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'pinterest') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE social_media_links MODIFY COLUMN platform ENUM('facebook', 'twitter', 'instagram', 'linkedin', 'youtube') NOT NULL");
    }
};
