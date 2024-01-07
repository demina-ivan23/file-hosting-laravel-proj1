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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('title')->nullable();  
            $table->string('description')->nullable();  
            $table->string('category')->nullable();
            $table->unsignedBigInteger('sender_id');
            $table->index('sender_id');
            $table->foreign('sender_id')
            ->references('id')
            ->on('users')
            ->onDelete('CASCADE');
            $table->unsignedBigInteger('reciever_id');
            $table->index('reciever_id');
            $table->foreign('reciever_id')
            ->references('id')
            ->on('users')
            ->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
