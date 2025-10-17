<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('title'); // Video Title
            $table->string('video')->nullable(); // Video URL (stored in S3 or local)
            $table->string('thumbnail')->nullable(); // Thumbnail URL (nullable)
            $table->text('description')->nullable(); // Optional description
            $table->boolean('is_enabled')->default(true); // Status Flag (Enabled/Disabled)
            $table->boolean('is_bulk_uploaded')->default(false); // If uploaded via bulk import
            $table->enum('status', ['uploading', 'processing', 'completed', 'failed'])->default('uploading');
            $table->integer('order')->default(0); // Order for sorting within a session
            $table->timestamps(); // Created At & Updated At
        });
    }

    public function down()
    {
        Schema::dropIfExists('videos');
    }
};
