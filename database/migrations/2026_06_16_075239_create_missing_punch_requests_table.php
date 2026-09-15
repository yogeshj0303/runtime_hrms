<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('missing_punch_requests', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('employee_id');

            $table->date('attendance_date');

            $table->dateTime('requested_punch_in')->nullable();
            $table->dateTime('requested_punch_out')->nullable();

            $table->enum('request_type', [
                'punch_in',
                'punch_out',
                'both'
            ]);

            $table->text('reason');

            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->unsignedBigInteger('approved_by')->nullable();

            $table->text('admin_remark')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->foreign('business_id', 'mpr_business_fk')
                ->references('id')
                ->on('businesses')
                ->onDelete('cascade');

            $table->foreign('employee_id', 'mpr_employee_fk')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('approved_by', 'mpr_approved_fk')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('missing_punch_requests');
    }
};