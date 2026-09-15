<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE `employee_addresses` MODIFY `type` VARCHAR(50) DEFAULT 'Permanent'");
        } catch (\Exception $e) {
            Schema::table('employee_addresses', function (Blueprint $table) {
                $table->string('type', 50)->default('Permanent')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE `employee_addresses` MODIFY `type` ENUM('permanent', 'current') DEFAULT 'permanent'");
        } catch (\Exception $e) {
            // fallback
        }
    }
};
