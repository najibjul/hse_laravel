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
        Schema::create('qrp_approvals', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('qrp_detail_id');
            $table->foreign('qrp_detail_id')->references('id')->on('qrp_details');

            $table->unsignedBigInteger('approval_id');    
            $table->foreign('approval_id')->references('id')->on('users');

            $table->dateTime('approved_at')->nullable();

            $table->string('status')->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qrp_approvals', function (Blueprint $table) {
            $table->dropForeign(['qrp_detail_id', 'approval_id']);
        });
        Schema::dropIfExists('qrp_approvals');
    }
};
