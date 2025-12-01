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
        Schema::create('package_upgrade_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tool_type'); // 'go-invoice' hoặc 'go-soft'
            
            // Thông tin gói cũ
            $table->unsignedBigInteger('old_package_id')->nullable();
            $table->decimal('old_package_price', 10, 2)->nullable();
            $table->timestamp('old_expires_at')->nullable();
            
            // Thông tin gói mới
            $table->unsignedBigInteger('new_package_id');
            $table->decimal('new_package_price', 10, 2);
            $table->timestamp('new_expires_at'); // Thời hạn mới tính từ thời điểm up gói
            
            // Tính toán giá
            $table->decimal('price_difference', 10, 2); // Chênh lệch giá (new - old)
            $table->integer('upgrade_count'); // Số lần up gói của user (1, 2, 3...)
            $table->integer('days_used')->default(0); // Số ngày đã sử dụng gói cũ
            $table->boolean('is_first_month')->default(false); // Có phải trong tháng đầu không
            $table->boolean('is_expired')->default(false); // Gói cũ đã hết hạn chưa
            
            // Giảm giá
            $table->decimal('discount_percent', 5, 2); // % giảm giá được áp dụng
            $table->decimal('discount_amount', 10, 2); // Số tiền được giảm
            $table->decimal('final_amount', 10, 2); // Số tiền cuối cùng phải trả
            
            // Liên kết với purchase
            $table->unsignedBigInteger('purchase_id')->nullable(); // ID của purchase record
            
            $table->text('note')->nullable(); // Ghi chú
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'tool_type']);
            $table->index('purchase_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_upgrade_histories');
    }
};

