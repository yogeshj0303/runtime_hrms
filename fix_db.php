<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('letter_templates', function (Blueprint $table) {
    if (!Schema::hasColumn('letter_templates', 'business_id')) {
        $table->foreignId('business_id')->nullable()->after('id')->constrained('businesses')->onDelete('cascade');
    }
    if (!Schema::hasColumn('letter_templates', 'user_id')) {
        $table->foreignId('user_id')->nullable()->after('business_id')->constrained('users')->onDelete('cascade');
    }
});

echo "Columns checked/added successfully!\n";
