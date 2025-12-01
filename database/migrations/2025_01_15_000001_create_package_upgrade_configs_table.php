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
        Schema::create('package_upgrade_configs', function (Blueprint $table) {
            $table->id();
            $table->string('tool_type')->unique(); // 'go-invoice' hoặc 'go-soft'
            
            // Các mức giảm giá (%)
            $table->decimal('first_upgrade_discount_first_month', 5, 2)->default(30.00); // Lần đầu up gói trong tháng đầu: 30%
            $table->decimal('second_upgrade_discount_first_month', 5, 2)->default(20.00); // Lần 2 up gói trong tháng đầu: 20%
            $table->decimal('subsequent_upgrade_discount_first_month', 5, 2)->default(20.00); // Lần 3-4-5 up gói trong tháng đầu: 20%
            $table->decimal('upgrade_discount_after_first_month', 5, 2)->default(10.00); // Up gói sau tháng đầu: 10%
            $table->decimal('renewal_discount_after_expired', 5, 2)->default(5.00); // Gia hạn sau khi hết hạn: 5%
            $table->decimal('cross_product_discount', 5, 2)->default(10.00); // Giảm khi mua sản phẩm khác: 10%
            
            $table->string('status')->default('active'); // active/inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_upgrade_configs');
    }
};

