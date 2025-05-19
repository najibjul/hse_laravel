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
        Schema::create('qrp_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_check_id');
            $table->foreign('daily_check_id')->references('id')->on('daily_checks');
            $table->string('description');
            $table->string('before');
            $table->string('recomendation');
            $table->unsignedBigInteger('dept_head_id')->nullable();
            $table->foreign('dept_head_id')->references('id')->on('users');
            $table->dateTime('dept_head_approved_at')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('users');
            $table->dateTime('admin_approved_at')->nullable();
            $table->string('after')->nullable();
            $table->unsignedBigInteger('qrp_status_id');
            $table->foreign('qrp_status_id')->references('id')->on('qrp_statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qrp_details');
    }
};
