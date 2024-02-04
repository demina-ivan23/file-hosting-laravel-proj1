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
        Schema::create('user_contact_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sender_id');
            $table->index('sender_id');
            $table->foreign('sender_id')
            ->references('publicId')
            ->on('users')
            ->onDelete('CASCADE');
            $table->uuid('receiver_id');
            $table->index('receiver_id');
            $table->foreign('receiver_id')
            ->references('publicId')
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
        Schema::dropIfExists('user_contact_requests');
    }
};
