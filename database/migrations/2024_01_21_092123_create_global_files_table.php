<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('global_files', function (Blueprint $table) {
            $table->id();
            $table->string('publicId');

            $table->string('path');
            $table->string('title')->nullable();  
            $table->string('description')->nullable();  
            $table->string('category')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('downloads')->default(0);
            $table->unsignedBigInteger('likes')->default(0);
            $table->boolean('isPublic');
            $table->string('state');
            $table->string('mimeType');
            
            $table->unsignedBigInteger('owner_id');
            $table->index('owner_id');
            $table->foreign('owner_id')
            ->references('id')
            ->on('users')
            ->onDelete('CASCADE');
            
            $table->date('expireDate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_files');
    }
};
