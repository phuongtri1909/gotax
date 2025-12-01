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
        $tables = ['go_invoice_purchases', 'go_soft_purchases', 'go_bot_purchases', 'go_quick_purchases'];
        
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->decimal('discount_percent', 5, 2)->nullable()->after('amount');
                $table->integer('discount_amount')->nullable()->after('discount_percent');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['go_invoice_purchases', 'go_soft_purchases', 'go_bot_purchases', 'go_quick_purchases'];
        
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(['discount_percent', 'discount_amount']);
            });
        }
    }
};
