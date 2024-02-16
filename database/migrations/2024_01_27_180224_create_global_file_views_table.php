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
        Schema::create('global_file_views', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('viewer_id');
            $table->index('viewer_id');
            $table->foreign('viewer_id')
            ->references('id')
            ->on('canvas_cookies')
            ->onDelete('CASCADE');

            $table->unsignedBigInteger('global_file');
            $table->index('global_file');
            $table->foreign('global_file')
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
        Schema::dropIfExists('global_file_views');
    }
};
