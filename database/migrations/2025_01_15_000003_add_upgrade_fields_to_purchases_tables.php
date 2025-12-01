<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Thêm các field để đánh dấu purchase là upgrade hay mua mới
     */
    public function up(): void
    {
        // Thêm field vào go_invoice_purchases
        Schema::table('go_invoice_purchases', function (Blueprint $table) {
            $table->boolean('is_upgrade')->default(false)->after('status'); // Đánh dấu là upgrade
            $table->unsignedBigInteger('upgrade_history_id')->nullable()->after('is_upgrade'); // Liên kết với upgrade history
            $table->unsignedBigInteger('old_package_id')->nullable()->after('upgrade_history_id'); // Gói cũ (nếu là upgrade)
            
            $table->index('is_upgrade');
            $table->index('upgrade_history_id');
        });

        // Thêm field vào go_soft_purchases
        Schema::table('go_soft_purchases', function (Blueprint $table) {
            $table->boolean('is_upgrade')->default(false)->after('status'); // Đánh dấu là upgrade
            $table->unsignedBigInteger('upgrade_history_id')->nullable()->after('is_upgrade'); // Liên kết với upgrade history
            $table->unsignedBigInteger('old_package_id')->nullable()->after('upgrade_history_id'); // Gói cũ (nếu là upgrade)
            
            $table->index('is_upgrade');
            $table->index('upgrade_history_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('go_invoice_purchases', function (Blueprint $table) {
            $table->dropIndex(['is_upgrade']);
            $table->dropIndex(['upgrade_history_id']);
            $table->dropColumn(['is_upgrade', 'upgrade_history_id', 'old_package_id']);
        });

        Schema::table('go_soft_purchases', function (Blueprint $table) {
            $table->dropIndex(['is_upgrade']);
            $table->dropIndex(['upgrade_history_id']);
            $table->dropColumn(['is_upgrade', 'upgrade_history_id', 'old_package_id']);
        });
    }
};

