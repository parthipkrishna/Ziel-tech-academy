<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('image'); // Mandatory
            $table->enum('type', ['course', 'toolkit'])->default('course'); // Type of banner
            $table->string('related_id'); // Mandatory, ID associated with the type
            $table->text('short_description')->nullable(); // Optional short description
            $table->boolean('status')->default(true); // Enable/Disable status
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
