<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id')->references('id')->on('users');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('admin_departments', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropForeign(['department_id']);
        });

        Schema::dropIfExists('admin_departments');
    }
};