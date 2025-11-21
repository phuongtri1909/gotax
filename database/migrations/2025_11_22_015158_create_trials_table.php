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
        Schema::create('trials', function (Blueprint $table) {
            $table->id();
            $table->string('tool_type')->unique(); // go-invoice, go-bot, go-soft, go-quick
            $table->integer('mst_limit')->nullable(); // Cho go-invoice, go-bot, go-soft
            $table->integer('cccd_limit')->nullable(); // Cho go-quick
            $table->integer('expires_days')->nullable(); // Số ngày dùng thử (cho go-invoice, go-soft)
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trials');
    }
};
