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
        Schema::create('qrp_recomendations', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('qrp_detail_id');
            $table->foreign('qrp_detail_id')->references('id')->on('qrp_details');
            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('recomendation');

            $table->tinyInteger('status')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qrp_recomendations', function (Blueprint $table) {
            $table->dropForeign(['qrp_detail_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('qrp_recomendations');
    }
};
