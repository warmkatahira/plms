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
        Schema::create('ride_details', function (Blueprint $table) {
            $table->increments('ride_detail_id');
            $table->unsignedInteger('ride_id');
            $table->unsignedInteger('boarding_location_id');
            $table->string('location_name', 10);
            $table->string('location_memo', 50)->nullable();
            $table->unsignedTinyInteger('stop_order');
            $table->time('arrival_time')->nullable();
            $table->time('departure_time')->nullable();
            $table->timestamps();
            // 外部キー
            $table->foreign('ride_id')->references('ride_id')->on('rides')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_details');
    }
};
