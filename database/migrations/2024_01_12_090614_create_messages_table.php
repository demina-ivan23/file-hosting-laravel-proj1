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
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('text');
            $table->boolean('system')->default(false);
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
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
