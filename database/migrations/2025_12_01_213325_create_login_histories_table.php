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
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // IP and Location
            $table->string('ip_address', 45);
            $table->string('country', 100)->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('region_code', 10)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('timezone', 50)->nullable();
            $table->string('isp', 255)->nullable();

            // Device and Browser Info
            $table->string('user_agent', 500)->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('browser_version', 50)->nullable();
            $table->string('platform', 100)->nullable(); // OS
            $table->string('platform_version', 50)->nullable();
            $table->string('device', 100)->nullable(); // Mobile, Desktop, Tablet
            $table->string('device_model', 100)->nullable();

            // Login Status
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->text('failure_reason')->nullable();

            $table->timestamps();

            // Indexes for better query performance
            $table->index('user_id');
            $table->index('ip_address');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};
