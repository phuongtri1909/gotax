<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Jobs table
        Schema::create('jobs_tools', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->string('tool', 50); // go-soft, go-quick, go-invoice, go-bot
            $table->string('action', 100); // crawl_tokhai, process_cccd, etc.
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->integer('progress')->default(0); // 0-100
            $table->text('result')->nullable();
            $table->text('error')->nullable();
            $table->json('params')->nullable(); // Job parameters
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            $table->index(['user_id', 'status']);
            $table->index(['tool', 'status']);
            $table->index('created_at');
        });

        // Job progress table (for history)
        Schema::create('jobs_progress', function (Blueprint $table) {
            $table->id();
            $table->string('job_tools_id');
            $table->string('step', 100)->nullable();
            $table->text('message')->nullable();
            $table->integer('percent')->default(0);
            $table->json('data')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('job_tools_id');
            $table->foreign('job_tools_id')->references('id')->on('jobs_tools')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs_progress');
        Schema::dropIfExists('jobs_tools');
    }
};