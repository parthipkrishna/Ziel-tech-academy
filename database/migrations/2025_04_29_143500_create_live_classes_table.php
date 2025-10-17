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
        Schema::create('live_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('tutor_id');
            $table->unsignedBigInteger('batch_id');
            $table->foreignId('subject_session_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('meeting_link');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('thumbnail_image')->nullable();
            $table->text('short_summary')->nullable();
            $table->text('summary')->nullable();
            $table->enum('status', ['Pending', 'Ongoing', 'Completed'])->default('Pending');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('tutor_id')->references('id')->on('tutors')->onDelete('cascade');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_classes');
    }
};
