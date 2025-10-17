<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_points', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('student_id')->index();          // who earned the points
            $table->unsignedBigInteger('referral_use_id')->nullable()->index(); // reference to referral usage
            $table->enum('type', ['earned','redeemed'])->index();       // earned or redeemed
            $table->unsignedInteger('points');                          // points amount
            $table->string('source', 100)->nullable();                  // 'referral', 'course', 'toolkit'
            $table->text('notes')->nullable();                          // optional note
            $table->timestamps();

            // Manual foreign keys
            $table->foreign('student_id', 'fk_referral_points_student')
                  ->references('id')->on('students')
                  ->onDelete('cascade');

            $table->foreign('referral_use_id', 'fk_referral_points_referral')
                  ->references('id')->on('referral_uses')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_points');
    }
};
