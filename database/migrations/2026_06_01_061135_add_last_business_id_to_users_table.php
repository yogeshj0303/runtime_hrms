<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('last_business_id')
                  ->nullable()
                  ->after('remember_token');

            $table->foreign('last_business_id')
                  ->references('id')
                  ->on('businesses')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['last_business_id']);
            $table->dropColumn('last_business_id');
        });
    }
};