<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('toolkit_enquiries', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->unsignedBigInteger('toolkit_id')->index();
            $table->unsignedBigInteger('student_id')->index();

            // Student snapshot (kept even if student profile changes later)
            $table->string('student_name');
            $table->string('state')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();

            // Toolkit snapshot
            $table->string('toolkit_name');
            $table->decimal('total_amount', 10, 2)->nullable();

            // Simple request lifecycle
            $table->enum('status', [
                'request_placed',
                'cancelled',
                'delivered'
            ])->default('request_placed');

            $table->timestamps();

            // Indexes
            $table->index(['toolkit_id', 'student_id', 'status']);

            // Foreign keys
            $table->foreign('toolkit_id')->references('id')->on('tool_kits')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toolkit_enquiries');
    }
};
