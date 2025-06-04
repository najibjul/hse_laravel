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
            $table->json('recomendation');
            
            $table->unsignedBigInteger('adh_id')->nullable();
            $table->foreign('adh_id')->references('id')->on('users');
            $table->dateTime('adh_approve_date')->nullable();

            $table->unsignedBigInteger('dh_id')->nullable();
            $table->foreign('dh_id')->references('id')->on('users');
            $table->dateTime('dh_approve_date')->nullable();
            
            $table->unsignedBigInteger('ph_id')->nullable();
            $table->foreign('ph_id')->references('id')->on('users');
            $table->dateTime('ph_approve_date')->nullable();

            $table->unsignedBigInteger('hse_id')->nullable();
            $table->foreign('hse_id')->references('id')->on('users');
            $table->dateTime('hse_approve_date')->nullable();

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
