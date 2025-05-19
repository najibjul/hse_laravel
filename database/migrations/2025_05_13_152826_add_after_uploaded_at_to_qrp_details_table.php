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
            $table->dateTime('after_uploaded_at')->nullable()->after('after');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qrp_details', function (Blueprint $table) {
            $table->dropColumn('after_uploaded_at');
        });
    }
};
