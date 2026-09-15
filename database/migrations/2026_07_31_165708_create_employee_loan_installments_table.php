<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('employee_loan_installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_loan_id');
            $table->date('installment_date');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('Pending');
            $table->timestamps();

            $table->foreign('employee_loan_id')->references('id')->on('employee_loans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_loan_installments');
    }
};
