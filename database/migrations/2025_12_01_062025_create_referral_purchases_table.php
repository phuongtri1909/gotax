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
        Schema::create('referral_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('referred_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('referral_code', 10);
            $table->string('tool_type', 20);
            $table->unsignedBigInteger('purchase_id');
            $table->string('transaction_code');
            $table->integer('amount');
            $table->string('status')->default('pending');
            $table->timestamp('purchase_date')->nullable();
            $table->timestamps();
            
            $table->index(['referrer_id', 'tool_type']);
            $table->index(['referred_user_id', 'tool_type']);
            $table->index('referral_code');
            $table->index('transaction_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_purchases');
    }
};
