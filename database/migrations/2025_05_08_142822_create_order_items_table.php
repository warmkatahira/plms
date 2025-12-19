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
        Schema::create('order_items', function (Blueprint $table){
            $table->increments('order_item_id');
            $table->string('order_control_id', 16);
            $table->boolean('is_item_allocated')->default(false);
            $table->boolean('is_stock_allocated')->default(false);
            // ここから受注データの内容
            $table->string('order_item_system_code', 255)->nullable();
            $table->string('order_item_code', 255)->nullable();
            $table->string('order_item_name', 500)->nullable();
            $table->unsignedInteger('order_quantity');
            $table->unsignedInteger('order_item_price')->nullable();
            // ここまで受注データの内容
            $table->unsignedInteger('allocated_item_id')->nullable();
            $table->unsignedInteger('allocated_set_item_id')->nullable();
            $table->string('item_name_snapshot', 255)->nullable();
            $table->boolean('is_auto_process_add')->default(false);
            $table->timestamps();
            // 外部キー
            $table->foreign('order_control_id')->references('order_control_id')->on('orders')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
