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
        Schema::create('ride_users', function (Blueprint $table) {
            $table->increments('ride_user_id');
            $table->unsignedInteger('ride_detail_id');
            $table->unsignedInteger('user_no');
            $table->timestamps();
            // 外部キー
            $table->foreign('ride_detail_id')->references('ride_detail_id')->on('ride_details')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('user_no')->references('user_no')->on('users')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_users');
    }
};
