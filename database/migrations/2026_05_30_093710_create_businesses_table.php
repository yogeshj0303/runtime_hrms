<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();

            // User Reference
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Business Details
            $table->string('business_name');
            $table->string('pan_number', 10)->nullable();

            // Address
            $table->text('address');
            $table->string('city');
            $table->string('pincode', 10);
            $table->string('state');

            // Business Type
            $table->string('business_constitution');

            // Setup Progress
            $table->tinyInteger('current_step')->default(1);
            $table->boolean('is_completed')->default(false);

            // Status
            $table->enum('status', ['draft', 'active', 'inactive'])
                  ->default('draft');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};