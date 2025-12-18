<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `jobs_tools` MODIFY COLUMN `status` ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE `jobs_tools` SET `status` = 'failed' WHERE `status` = 'cancelled'");
        DB::statement("ALTER TABLE `jobs_tools` MODIFY COLUMN `status` ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending'");
    }
};
