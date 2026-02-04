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
        Schema::create('route_details', function (Blueprint $table) {
            $table->increments('route_detail_id');
            $table->unsignedInteger('route_id');
            $table->unsignedInteger('boarding_location_id');
            $table->unsignedTinyInteger('stop_order');
            $table->time('arrival_time')->nullable();
            $table->time('departure_time')->nullable();
            $table->timestamps();
            // 外部キー
            $table->foreign('route_id')->references('route_id')->on('routes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('boarding_location_id')->references('boarding_location_id')->on('boarding_locations')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_details');
    }
};
