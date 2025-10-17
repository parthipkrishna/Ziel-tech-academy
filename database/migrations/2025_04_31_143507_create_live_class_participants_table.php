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
        Schema::create('live_class_participants', function (Blueprint $table) {
            $table->id();

            // Foreign key to live_classes
            $table->unsignedBigInteger('live_class_id');
            $table->foreign('live_class_id')
                ->references('id')->on('live_classes')
                ->onDelete('cascade');
            $table->index('live_class_id');

            // Foreign key to students (or users)
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')
                ->references('id')->on('students') // change to users if needed
                ->onDelete('cascade');
            $table->index('student_id');

            // Foreign key to batches
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->foreign('batch_id')
                ->references('id')->on('batches')
                ->onDelete('set null');
            $table->index('batch_id');

            // Participation details
            $table->dateTime('join_time')->nullable();
            $table->dateTime('leave_time')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_class_participants');
    }
};
