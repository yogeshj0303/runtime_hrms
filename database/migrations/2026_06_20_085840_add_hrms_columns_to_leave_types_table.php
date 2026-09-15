<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {

            $table->string('short_name')->nullable()->after('name');
            $table->string('color')->nullable()->after('short_name');

            $table->boolean('is_paid_leave')->default(false);
            $table->boolean('maintain_leave_balance')->default(false);

            $table->boolean('allow_leave_requests')->default(true);
            $table->boolean('allow_future_requests')->default(true);

            $table->enum('probation_rule', ['allow', 'disallow'])
                  ->default('allow');

            $table->integer('advance_leave_days')
                  ->default(0)
                  ->comment('Allow advance leave(s)');

            $table->integer('past_request_days')
                  ->default(0)
                  ->comment('Requests allowed for day(s) in past');

            $table->integer('monthly_limit')
                  ->default(0)
                  ->comment('Leave(s) allowed every month');
        });
    }

    public function down(): void
    {
        Schema::table('leave_types', function (Blueprint $table) {
            $table->dropColumn([
                'short_name',
                'color',
                'is_paid_leave',
                'maintain_leave_balance',
                'allow_leave_requests',
                'allow_future_requests',
                'probation_rule',
                'advance_leave_days',
                'past_request_days',
                'monthly_limit'
            ]);
        });
    }
};