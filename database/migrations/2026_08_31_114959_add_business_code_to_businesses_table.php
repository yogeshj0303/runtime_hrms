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
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('business_code')->nullable()->unique()->after('id');
        });

        // Backfill existing businesses
        $businesses = DB::table('businesses')->orderBy('id')->get();
        foreach ($businesses as $business) {
            $code = 'BUS' . str_pad($business->id, 4, '0', STR_PAD_LEFT);
            DB::table('businesses')->where('id', $business->id)->update(['business_code' => $code]);
            
            // Also backfill existing employees
            DB::table('employees')->where('business_id', $business->id)->update(['business_code' => $code]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('business_code');
        });
    }
};

