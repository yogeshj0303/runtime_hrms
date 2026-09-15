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
        Schema::create('wall_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wall_post_id');
            $table->unsignedBigInteger('employee_id');
            $table->timestamps();
            
            $table->foreign('wall_post_id')->references('id')->on('wall_posts')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            
            $table->unique(['wall_post_id', 'employee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wall_likes');
    }
};
