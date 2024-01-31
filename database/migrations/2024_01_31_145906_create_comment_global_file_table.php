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
        Schema::create('comment_global_file', function (Blueprint $table) {
            $table->id();

            $table->uuid('comment_id');
            $table->index('comment_id');
            $table->foreign('comment_id')
            ->references('id')
            ->on('comments')
            ->onDelete('CASCADE');

            $table->unsignedBigInteger('global_file_id');
            $table->index('global_file_id');
            $table->foreign('global_file_id')
            ->references('id')
            ->on('global_files')
            ->onDelete('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_global_file');
    }
};
