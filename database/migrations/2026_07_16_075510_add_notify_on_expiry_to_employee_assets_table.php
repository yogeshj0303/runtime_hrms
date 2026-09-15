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
        Schema::table('employee_assets', function (Blueprint $table) {
            $table->boolean('notify_on_expiry')->default(false)->after('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_assets', function (Blueprint $table) {
            $table->dropColumn(['notify_on_expiry']);
        });
    }
};
