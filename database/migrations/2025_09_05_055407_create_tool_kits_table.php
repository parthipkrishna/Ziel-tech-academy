<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tool_kits', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('course_id')->index(); // manual FK + index
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('short_description', 255)->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('offer_price', 10, 2)->nullable();
            $table->unsignedInteger('min_loyalty_points')->default(0);
            $table->unsignedInteger('loyalty_points_earn')->default(0);
            $table->timestamps();

            // Foreign key manually
            $table->foreign('course_id')
                  ->references('id')->on('courses')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_kits');
    }
};
