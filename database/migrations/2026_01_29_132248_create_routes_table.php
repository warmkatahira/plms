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
        Schema::create('routes', function (Blueprint $table) {
            $table->increments('route_id');
            $table->string('route_name', 20)->unique();
            $table->unsignedInteger('vehicle_category_id');
            $table->unsignedInteger('route_type_id');
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('sort_order')->default(100);
            $table->timestamps();
            // 外部キー
            $table->foreign('vehicle_category_id')->references('vehicle_category_id')->on('vehicle_categories')->cascadeOnUpdate();
            $table->foreign('route_type_id')->references('route_type_id')->on('route_types')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
