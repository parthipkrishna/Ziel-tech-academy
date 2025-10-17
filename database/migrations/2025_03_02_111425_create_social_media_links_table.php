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
        Schema::create('social_media_links', function (Blueprint $table) {
            $table->id(); // BIGINT Primary Key, Auto-increment
            $table->enum('platform', ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'])->notNullable();
            $table->string('url', 255)->notNullable(); // Profile/page URL
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_links');
    }
};
