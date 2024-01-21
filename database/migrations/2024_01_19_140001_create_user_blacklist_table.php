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
        Schema::create('user_blacklist', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('blocker_id');
            $table->index('blocker_id');
            $table->foreign('blocker_id')
            ->references('is')
            ->on('users')
            ->onDelete('CASCADE');

            $table->unsignedBigInteger('blocked_user_id');
            $table->index('blocked_user_id');
            $table->foreign('blocked_user_id')
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
        Schema::dropIfExists('user_blacklist');
    }
};
