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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->enum('type', [1, 2])->comment('1 => Deposit, 2 => Withdrawal');
            $table->decimal('amount', 10, 2, true);
            $table->decimal('commission', 10, 2, true)->default(0.00);
            $table->foreignId('method_id')->constrained(
                table: 'payment_methods',
                indexName: 'transaction_payment_method_id'
            );
            $table->foreignId('sender_id')->constrained(
                table: 'users',
                indexName: 'transaction_sender_id'
            );
            $table->enum('sender_account', [1, 2, 3])->comment('1 => main, 2 => commission, 3 => cash');
            $table->decimal('sender_account_balance', 10, 2);
            $table->foreignId('receiver_id')->constrained(
                table: 'users',
                indexName: 'transaction_receiver_id'
            );
            $table->string('receiver_account')->comment('Either Main OR Commission account');
            $table->decimal('receiver_account_balance', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
