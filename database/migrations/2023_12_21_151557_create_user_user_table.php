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
        Schema::create('user_user', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id_1');
            $table->index('user_id_1');
            $table->foreign('user_id_1')
            ->references('punlicId')
            ->on('users')
            ->onDelete('CASCADE');

            $table->uuid('user_id_2');
            $table->index('user_id_2');
            $table->foreign('user_id_2')
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
        Schema::dropIfExists('user_contact_user');
    }
};
