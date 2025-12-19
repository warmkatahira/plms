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
        Schema::create('order_categories', function (Blueprint $table) {
            $table->increments('order_category_id');
            $table->string('order_category_name', 100);
            $table->unsignedInteger('mall_id');
            $table->unsignedInteger('shipper_id');
            $table->string('nifuda_product_name_1', 16);
            $table->string('nifuda_product_name_2', 16)->nullable();
            $table->string('nifuda_product_name_3', 16)->nullable();
            $table->string('nifuda_product_name_4', 16)->nullable();
            $table->string('nifuda_product_name_5', 16)->nullable();
            $table->string('app_id', 10)->nullable();
            $table->string('access_token', 255)->nullable();
            $table->string('api_key', 255)->nullable();
            $table->unsignedInteger('sort_order');
            $table->timestamps();
            // 外部キー
            $table->foreign('mall_id')->references('mall_id')->on('malls');
            $table->foreign('shipper_id')->references('shipper_id')->on('shippers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_categories');
    }
};
