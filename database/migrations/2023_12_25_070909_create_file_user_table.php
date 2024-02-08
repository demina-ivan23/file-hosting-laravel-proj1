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
        Schema::create('file_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userSender');
            $table->index('userSender');
            $table->foreign('userSender')
            ->references('id')
            ->on('users')
            ->onDelete('CASCADE');

            
            $table->unsignedBigInteger('userReceiver');
            $table->index('userReceiver');
            $table->foreign('userReceiver')
            ->references('id')
            ->on('users')
            ->onDelete('CASCADE');


            $table->uuid('file');
            $table->index('file');
            $table->foreign('file')
            ->references('id')
            ->on('files')
            ->onDelete('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files_users');
    }
};
