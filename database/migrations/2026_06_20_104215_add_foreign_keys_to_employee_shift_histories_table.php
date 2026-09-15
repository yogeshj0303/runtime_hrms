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
Schema::table('employee_shift_histories', function (Blueprint $table) {


    $table->foreign('business_id')
        ->references('id')
        ->on('businesses')
        ->cascadeOnDelete();

    $table->foreign('employee_id')
        ->references('id')
        ->on('users')
        ->cascadeOnDelete();

    $table->foreign('shift_id')
        ->references('id')
        ->on('shifts')
        ->cascadeOnDelete();

    $table->foreign('assigned_by')
        ->references('id')
        ->on('users')
        ->cascadeOnDelete();

});


}

public function down(): void
{
Schema::table('employee_shift_histories', function (Blueprint $table) {


    $table->dropForeign(['business_id']);
    $table->dropForeign(['employee_id']);
    $table->dropForeign(['shift_id']);
    $table->dropForeign(['assigned_by']);

});


}

};
