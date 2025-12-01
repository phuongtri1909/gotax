<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Chuyển đổi tất cả các cột tiền từ decimal(10,2) sang integer (lưu theo đồng, không có xu)
     */
    public function up(): void
    {
        // Packages tables
        Schema::table('go_invoice_packages', function (Blueprint $table) {
            $table->integer('price')->change();
            $table->integer('license_fee')->default(0)->change();
        });

        Schema::table('go_soft_packages', function (Blueprint $table) {
            $table->integer('price')->change();
        });

        Schema::table('go_bot_packages', function (Blueprint $table) {
            $table->integer('price')->change();
        });

        Schema::table('go_quick_packages', function (Blueprint $table) {
            $table->integer('price')->change();
        });

        // Purchases tables
        Schema::table('go_invoice_purchases', function (Blueprint $table) {
            $table->integer('amount')->change();
            $table->integer('license_fee')->nullable()->change();
        });

        Schema::table('go_soft_purchases', function (Blueprint $table) {
            $table->integer('amount')->change();
        });

        Schema::table('go_bot_purchases', function (Blueprint $table) {
            $table->integer('amount')->change();
        });

        Schema::table('go_quick_purchases', function (Blueprint $table) {
            $table->integer('amount')->change();
        });

        // Upgrade histories table
        Schema::table('package_upgrade_histories', function (Blueprint $table) {
            $table->integer('old_package_price')->nullable()->change();
            $table->integer('new_package_price')->change();
            $table->integer('price_difference')->change();
            $table->integer('discount_amount')->change();
            $table->integer('final_amount')->change();
            $table->integer('remaining_value')->nullable()->change();
            $table->integer('new_package_value_for_remaining')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Packages tables
        Schema::table('go_invoice_packages', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
            $table->decimal('license_fee', 10, 2)->default(0)->change();
        });

        Schema::table('go_soft_packages', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
        });

        Schema::table('go_bot_packages', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
        });

        Schema::table('go_quick_packages', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
        });

        // Purchases tables
        Schema::table('go_invoice_purchases', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->change();
            $table->decimal('license_fee', 10, 2)->nullable()->change();
        });

        Schema::table('go_soft_purchases', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->change();
        });

        Schema::table('go_bot_purchases', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->change();
        });

        Schema::table('go_quick_purchases', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->change();
        });

        // Upgrade histories table
        Schema::table('package_upgrade_histories', function (Blueprint $table) {
            $table->decimal('old_package_price', 10, 2)->nullable()->change();
            $table->decimal('new_package_price', 10, 2)->change();
            $table->decimal('price_difference', 10, 2)->change();
            $table->decimal('discount_amount', 10, 2)->change();
            $table->decimal('final_amount', 10, 2)->change();
            $table->decimal('remaining_value', 10, 2)->nullable()->change();
            $table->decimal('new_package_value_for_remaining', 10, 2)->nullable()->change();
        });
    }
};

