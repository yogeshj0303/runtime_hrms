<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_units', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('business_id')
                ->constrained('businesses')
                ->cascadeOnDelete();

            $table->string('unit_name');

            $table->string('report_title');

            $table->text('sub_header_1')
                ->nullable();

            $table->text('sub_header_2')
                ->nullable();

            $table->text('footer_line_1')
                ->nullable();

            $table->text('footer_line_2')
                ->nullable();

            $table->boolean('is_default')
                ->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_units');
    }
};