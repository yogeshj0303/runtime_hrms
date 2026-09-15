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
        Schema::table('employee_documents', function (Blueprint $table) {
            // Drop existing foreign key pointing to users table if exists
            try {
                $table->dropForeign('employee_documents_employee_id_foreign');
            } catch (\Throwable $e) {
                // Ignore if not present
            }
        });

        Schema::table('employee_documents', function (Blueprint $table) {
            // Add foreign key pointing to employees table
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_documents', function (Blueprint $table) {
            try {
                $table->dropForeign(['employee_id']);
                $table->foreign('employee_id')
                    ->references('id')
                    ->on('users')
                    ->cascadeOnDelete();
            } catch (\Throwable $e) {
                // Ignore
            }
        });
    }
};
