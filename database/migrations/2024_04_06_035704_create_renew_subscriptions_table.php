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
        Schema::create('renew_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('decoder');
            $table->string('name');
            $table->foreignId('formula_id')->constrained(
                table: 'categories',
                indexName: 'category_renew_subscription_formula_id'
            );
            $table->integer('option_id')->nullable();
            $table->string('duration');
            $table->string('phone');
            $table->foreignId('method_id')->constrained(
                table: 'payment_methods',
                indexName: 'payment_method_id'
            );
            $table->string('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renew_subscriptions');
    }
};
