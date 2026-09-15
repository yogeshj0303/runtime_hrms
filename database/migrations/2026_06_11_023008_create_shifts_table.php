<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('business_id');

            $table->string('code');
            $table->string('name');

            $table->time('start_time');

            $table->integer('duration_hours')->default(0);
            $table->integer('duration_minutes')->default(0);

            $table->time('end_time');

            $table->integer('payable_hours')->default(9);
            $table->integer('payable_minutes')->default(0);

            $table->boolean('is_default')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shifts');
    }
};