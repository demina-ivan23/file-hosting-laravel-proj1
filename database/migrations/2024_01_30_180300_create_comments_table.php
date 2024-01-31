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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            $table->string('text');
            $table->integer('likes')->default(0);

            $table->unsignedBigInteger('user_id');
            $table->index('user_id');
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
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
        Schema::dropIfExists('comments');
    }
};
