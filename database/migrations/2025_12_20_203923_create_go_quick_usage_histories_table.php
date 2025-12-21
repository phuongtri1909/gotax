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
        Schema::create('go_quick_usage_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('go_quick_use_id')->nullable()->constrained()->nullOnDelete();
            $table->string('job_id')->nullable(); // jobs_tools.id is string, not unsignedBigInteger
            $table->enum('upload_type', ['excel', 'pdf', 'zip', 'images_single', 'images_batch']);
            $table->integer('batch_index')->nullable();
            $table->integer('total_batches')->nullable();
            $table->integer('estimated_cccd')->default(0);
            $table->integer('actual_cccd')->nullable();
            $table->integer('cccd_deducted')->default(0);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('job_id');
            $table->index('status');
            
            // Foreign key constraint for job_id (jobs_tools.id is string)
            $table->foreign('job_id')->references('id')->on('jobs_tools')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('go_quick_usage_histories');
    }
};
