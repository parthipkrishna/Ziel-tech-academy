<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tool_kit_media', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tool_kit_id')->index();
            $table->string('file_path'); // relative path or S3 URL

            $table->timestamps();

            $table->foreign('tool_kit_id')
                  ->references('id')->on('tool_kits')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tool_kit_media');
    }
};
