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
        Schema::table('package_upgrade_configs', function (Blueprint $table) {
            $table->decimal('first_upgrade_discount_first_month', 5, 2)->nullable()->change();
            $table->decimal('second_upgrade_discount_first_month', 5, 2)->nullable()->change();
            $table->decimal('subsequent_upgrade_discount_first_month', 5, 2)->nullable()->change();
            $table->decimal('upgrade_discount_after_first_month', 5, 2)->nullable()->change();
            $table->decimal('renewal_discount_after_expired', 5, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_upgrade_configs', function (Blueprint $table) {
            $table->decimal('first_upgrade_discount_first_month', 5, 2)->default(30.00)->nullable(false)->change();
            $table->decimal('second_upgrade_discount_first_month', 5, 2)->default(20.00)->nullable(false)->change();
            $table->decimal('subsequent_upgrade_discount_first_month', 5, 2)->default(20.00)->nullable(false)->change();
            $table->decimal('upgrade_discount_after_first_month', 5, 2)->default(10.00)->nullable(false)->change();
            $table->decimal('renewal_discount_after_expired', 5, 2)->default(5.00)->nullable(false)->change();
        });
    }
};
