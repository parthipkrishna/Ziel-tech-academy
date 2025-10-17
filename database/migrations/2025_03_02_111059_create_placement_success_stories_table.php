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
        Schema::create('placement_success_stories', function (Blueprint $table) {
            $table->id(); // BIGINT Primary Key, Auto-increment
            $table->unsignedBigInteger('alumni_id'); // Foreign Key (users)
            $table->unsignedBigInteger('placement_id'); // Foreign Key (placements)
            $table->text('story')->notNullable(); // Success story details
            $table->string('position', 255)->notNullable(); // Job role secured
            $table->date('joined_date')->notNullable(); // Date of joining
            $table->timestamps(); // created_at and updated_at

            // Foreign Key Constraints
            $table->foreign('alumni_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('placement_id')->references('id')->on('placements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placement_success_stories');
    }
};
