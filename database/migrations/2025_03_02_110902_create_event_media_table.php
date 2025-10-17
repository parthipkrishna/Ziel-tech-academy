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
        Schema::create('event_media', function (Blueprint $table) {
            $table->id(); // BIGINT Primary Key, Auto-increment
            $table->unsignedBigInteger('event_id'); // Foreign Key (events)
            $table->string('media_url', 255)->notNullable(); // URL of media
            $table->enum('type', ['image', 'video', 'youtube', 'event'])->notNullable(); // Media type
            $table->timestamps(); // created_at and updated_at

            // Foreign Key Constraint
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_media');
    }
};
