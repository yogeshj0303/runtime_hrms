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
        Schema::table('employee_login_accesses', function (Blueprint $table) {
            $table->boolean('pin_never_expires')->default(false)->after('pin');
            $table->boolean('make_wall_admin')->default(false);
            $table->boolean('allow_wall_posting')->default(false);
            $table->boolean('allow_wall_comments')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_login_accesses', function (Blueprint $table) {
            $table->dropColumn([
                'pin_never_expires',
                'make_wall_admin',
                'allow_wall_posting',
                'allow_wall_comments'
            ]);
        });
    }
};
