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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('vehicle_id');
            $table->unsignedInteger('user_no')->nullable();
            $table->unsignedInteger('vehicle_type_id');
            $table->unsignedInteger('vehicle_category_id');
            $table->string('vehicle_name', 10);
            $table->string('vehicle_color', 5);
            $table->string('vehicle_number', 4);
            $table->unsignedTinyInteger('vehicle_capacity');
            $table->string('vehicle_memo', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            // 外部キー
            $table->foreign('user_no')->references('user_no')->on('users')->cascadeOnUpdate();
            $table->foreign('vehicle_type_id')->references('vehicle_type_id')->on('vehicle_types')->cascadeOnUpdate();
            $table->foreign('vehicle_category_id')->references('vehicle_category_id')->on('vehicle_categories')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
