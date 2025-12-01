<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Thêm field first_purchase_discount cho cả 4 tool
     */
    public function up(): void
    {
        Schema::table('package_upgrade_configs', function (Blueprint $table) {
            $table->decimal('first_purchase_discount', 5, 2)->default(0.00)->after('cross_product_discount'); // Giảm % lần đầu mua (khác với lần đầu upgrade)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_upgrade_configs', function (Blueprint $table) {
            $table->dropColumn('first_purchase_discount');
        });
    }
};

