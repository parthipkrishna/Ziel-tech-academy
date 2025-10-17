<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_sessions', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('title'); // Video Part Title
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable(); // Optional description
            $table->timestamps(); // Created At & Updated At
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_sessions');
    }
};
