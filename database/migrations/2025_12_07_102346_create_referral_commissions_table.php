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
        Schema::create('referral_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('User who earned the commission');
            $table->foreignId('referred_user_id')->constrained('users')->onDelete('cascade')->comment('User who made the deposit');
            $table->foreignId('deposit_id')->constrained()->onDelete('cascade')->comment('Deposit that generated this commission');
            $table->integer('level')->comment('1, 2, or 3 representing the referral level');
            $table->decimal('deposit_amount', 15, 2)->comment('Amount deposited by referred user');
            $table->decimal('commission_percentage', 8, 2)->comment('Commission percentage applied');
            $table->decimal('commission_amount', 15, 2)->comment('Commission amount earned');
            $table->decimal('balance_before', 15, 2)->comment('User balance before commission');
            $table->decimal('balance_after', 15, 2)->comment('User balance after commission');
            $table->timestamps();

            // Indexes for better query performance
            $table->index(['user_id', 'created_at']);
            $table->index('referred_user_id');
            $table->index('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_commissions');
    }
};
