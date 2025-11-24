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
        Schema::create('go_bot_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('bank_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('package_id')->constrained('go_bot_packages')->cascadeOnDelete();
            $table->string('transaction_code')->unique();
            $table->decimal('amount', 10, 2);
            $table->integer('mst_limit');
            $table->string('status')->default('pending');
            $table->text('note')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->json('casso_response')->nullable();
            $table->string('casso_transaction_id')->nullable();
            
            // Customer info
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('vat_mst')->nullable();
            $table->string('vat_company')->nullable();
            $table->text('vat_address')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('go_bot_purchases');
    }
};
