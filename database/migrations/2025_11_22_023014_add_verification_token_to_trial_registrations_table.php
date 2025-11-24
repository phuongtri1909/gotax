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
        Schema::table('trial_registrations', function (Blueprint $table) {
            $table->string('verification_token', 100)->nullable()->after('is_read');
            $table->timestamp('verified_at')->nullable()->after('verification_token');
            $table->index('verification_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trial_registrations', function (Blueprint $table) {
            $table->dropIndex(['verification_token']);
            $table->dropColumn(['verification_token', 'verified_at']);
        });
    }
};
