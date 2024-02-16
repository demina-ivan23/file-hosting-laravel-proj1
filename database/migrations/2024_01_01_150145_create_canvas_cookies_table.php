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
        Schema::create('canvas_cookies', function (Blueprint $table) {
            $table->id();
            $table->string('canvasId');
            $table->unsignedBigInteger('userId')->nullable();
            $table->index('userId');
            $table->foreign('userId')
            ->references('id')
            ->on('users')
            ->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cookies');
    }
};
