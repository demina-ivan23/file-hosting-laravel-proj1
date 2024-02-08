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
        Schema::create('message_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userSender')->nullable();
            $table->index('userSender');
            $table->foreign('userSender')
            ->references('id')
            ->on('users')
            ->onDelete('SET NULL');

            $table->unsignedBigInteger('userReceiver');
            $table->index('userReceiver');
            $table->foreign('userReceiver')
            ->references('id')
            ->on('users')
            ->onDelete('CASCADE');

            $table->uuid('message');
            $table->index('message');
            $table->foreign('message')
            ->references('id')
            ->on('messages')
            ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_user');
    }
};
