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
        Schema::table('go_quick_uses', function (Blueprint $table) {
            $table->integer('total_used')->default(0)->after('cccd_limit');
            $table->integer('total_cccd_extracted')->default(0)->after('total_used');
        });
    }

    public function down(): void
    {
        Schema::table('go_quick_uses', function (Blueprint $table) {
            $table->dropColumn(['total_used', 'total_cccd_extracted']);
        });
    }
};
