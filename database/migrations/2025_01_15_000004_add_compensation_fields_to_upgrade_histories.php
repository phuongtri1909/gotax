<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Thêm các field để hỗ trợ bù trừ thời gian khi upgrade
     */
    public function up(): void
    {
        Schema::table('package_upgrade_histories', function (Blueprint $table) {
            $table->boolean('keep_current_expires')->default(false)->after('new_expires_at'); // Giữ nguyên thời hạn cũ
            $table->decimal('remaining_value', 10, 2)->nullable()->after('price_difference'); // Giá trị còn lại của gói cũ
            $table->decimal('new_package_value_for_remaining', 10, 2)->nullable()->after('remaining_value'); // Giá trị gói mới cho thời gian còn lại
            $table->integer('days_remaining')->default(0)->after('days_used'); // Số ngày còn lại
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_upgrade_histories', function (Blueprint $table) {
            $table->dropColumn([
                'keep_current_expires',
                'remaining_value',
                'new_package_value_for_remaining',
                'days_remaining'
            ]);
        });
    }
};

