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
        Schema::create('route_schedule_details', function (Blueprint $table) {
            $table->increments('route_schedule_detail_id');
            $table->unsignedInteger('route_schedule_id');
            $table->unsignedInteger('boarding_location_id');
            $table->string('location_name', 10);
            $table->string('location_memo', 50)->nullable();
            $table->unsignedTinyInteger('stop_order');
            $table->time('arrival_time')->nullable();
            $table->time('departure_time');
            $table->timestamps();
            // 外部キー
            $table->foreign('route_schedule_id')->references('route_schedule_id')->on('route_schedules')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_schedule_details');
    }
};
