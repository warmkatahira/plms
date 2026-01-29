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
        Schema::create('route_schedule_users', function (Blueprint $table) {
            $table->increments('route_schedule_user_id');
            $table->unsignedInteger('route_schedule_detail_id');
            $table->unsignedInteger('user_no');
            $table->timestamps();
            // 外部キー
            $table->foreign('route_schedule_detail_id')->references('route_schedule_detail_id')->on('route_schedule_details')->cascadeOnUpdate();
            $table->foreign('user_no')->references('user_no')->on('users')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_schedule_users');
    }
};
