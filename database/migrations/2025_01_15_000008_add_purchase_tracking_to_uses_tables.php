<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Thêm các trường để theo dõi số lần mua và ngày mua đầu tiên
     */
    public function up(): void
    {
        // GoInvoiceUse
        Schema::table('go_invoice_uses', function (Blueprint $table) {
            $table->integer('purchase_count')->default(1)->after('expires_at'); // Số lần mua gói hiện tại
            $table->timestamp('first_purchase_date')->nullable()->after('purchase_count'); // Ngày mua đầu tiên của gói hiện tại
        });

        // GoSoftUse
        Schema::table('go_soft_uses', function (Blueprint $table) {
            $table->integer('purchase_count')->default(1)->after('expires_at');
            $table->timestamp('first_purchase_date')->nullable()->after('purchase_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('go_invoice_uses', function (Blueprint $table) {
            $table->dropColumn(['purchase_count', 'first_purchase_date']);
        });

        Schema::table('go_soft_uses', function (Blueprint $table) {
            $table->dropColumn(['purchase_count', 'first_purchase_date']);
        });
    }
};

