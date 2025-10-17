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
        Schema::create('web_banners', function (Blueprint $table) {
            $table->id(); // BIGINT Primary Key, Auto-increment
            $table->string('title', 255)->nullable(); // Banner title
            $table->string('image_url', 255)->notNullable(); // URL of the banner image
            $table->enum('type', [
                'home', 'compus', 'promo', 'gallery', 'about us', 'placement', 'contact us', 'other', 'branches'
            ])->notNullable(); // Type of banner
            $table->string('short_desc', 255)->nullable(); // Short description
            $table->string('description', 255)->nullable(); // Description
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_banners');
    }
};
