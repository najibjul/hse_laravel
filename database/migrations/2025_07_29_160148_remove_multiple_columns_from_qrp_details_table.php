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
        Schema::table('qrp_details', function (Blueprint $table) {
            $table->dropForeign(['adh_id']);
            $table->dropForeign(['dh_id']);
            $table->dropForeign(['ph_id']);
            $table->dropForeign(['hse_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn(['recomendation', 'adh_id', 'dh_id', 'ph_id', 'hse_id', 'adh_approve_date', 'dh_approve_date', 'ph_approve_date', 'hse_approve_date', 'department_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qrp_details', function (Blueprint $table) {
            $table->string('recomendation')->nullable();
            $table->unsignedBigInteger('adh_id')->nullable();
            $table->foreign('adh_id')->references('id')->on('users');
            $table->unsignedBigInteger('dh_id')->nullable();
            $table->foreign('dh_id')->references('id')->on('users');
            $table->unsignedBigInteger('ph_id')->nullable();
            $table->foreign('ph_id')->references('id')->on('users');
            $table->unsignedBigInteger('hse_id')->nullable();
            $table->foreign('hse_id')->references('id')->on('users');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments');
            $table->dateTime('adh_approve_date')->nullable();
            $table->dateTime('dh_approve_date')->nullable();
            $table->dateTime('ph_approve_date')->nullable();
            $table->dateTime('hse_approve_date')->nullable();
        });
    }
};
