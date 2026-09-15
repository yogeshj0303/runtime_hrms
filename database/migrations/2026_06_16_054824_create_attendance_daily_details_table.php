<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_daily_details', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('attendance_daily_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('employee_id');

            $table->dateTime('punch_in_time')->nullable();
            $table->dateTime('punch_out_time')->nullable();

            $table->string('punch_in_location')->nullable();
            $table->string('punch_out_location')->nullable();

            $table->decimal('punch_in_latitude', 10, 8)->nullable();
            $table->decimal('punch_in_longitude', 11, 8)->nullable();

            $table->decimal('punch_out_latitude', 10, 8)->nullable();
            $table->decimal('punch_out_longitude', 11, 8)->nullable();

            $table->string('device_name')->nullable();
            $table->ipAddress('ip_address')->nullable();

            $table->integer('total_working_time')->default(0);

            $table->enum('status_daily', [
                'punched_in',
                'punched_out'
            ])->nullable();

            $table->text('remark')->nullable();

            $table->timestamps();

            $table->foreign('attendance_daily_id', 'att_detail_daily_fk')
                ->references('id')
                ->on('attendance_dailies')
                ->onDelete('cascade');

            $table->foreign('business_id', 'att_detail_business_fk')
                ->references('id')
                ->on('businesses')
                ->onDelete('cascade');

            $table->foreign('employee_id', 'att_detail_employee_fk')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_daily_details');
    }
};