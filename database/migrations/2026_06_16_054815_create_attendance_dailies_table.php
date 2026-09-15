<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_dailies', function (Blueprint $table) {

    $table->id();

    $table->unsignedBigInteger('business_id');
    $table->unsignedBigInteger('employee_id');

    $table->date('attendance_date');

    $table->string('day',20);

    $table->enum('status',[
        'present',
        'absent',
        'half_day',
        'leave'
    ])->default('absent');

    $table->integer('working_time_for_day')->default(0);

    $table->integer('total_working_time')->default(0);

    $table->boolean('is_late')->default(false);

    $table->integer('late_minutes')->default(0);

    $table->text('remark')->nullable();

    $table->timestamps();

    $table->foreign('business_id', 'att_daily_business_fk')
        ->references('id')
        ->on('businesses')
        ->onDelete('cascade');

    $table->foreign('employee_id', 'att_daily_employee_fk')
        ->references('id')
        ->on('users')
        ->onDelete('cascade');

    $table->unique(
        ['business_id', 'employee_id', 'attendance_date'],
        'att_daily_unique'
    );

});
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_dailies');
    }
};