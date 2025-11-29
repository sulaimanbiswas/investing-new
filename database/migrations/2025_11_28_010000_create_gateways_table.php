<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gateways', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['payment', 'withdrawal']);
            $table->string('name');
            $table->string('currency')->index();
            $table->string('country')->nullable();
            $table->decimal('rate_usdt', 18, 8)->comment('Value of currency in USDT');
            $table->enum('charge_type', ['fixed', 'percent']);
            $table->decimal('charge_value', 18, 8)->default(0);
            $table->decimal('min_limit', 18, 8)->nullable();
            $table->decimal('max_limit', 18, 8)->nullable();
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('qr_path')->nullable();
            $table->boolean('requires_txn_id')->default(false);
            $table->boolean('requires_screenshot')->default(false);
            $table->json('custom_fields')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gateways');
    }
};
