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
        Schema::create('order_item_components', function (Blueprint $table){
            $table->increments('order_item_component_id');
            $table->unsignedInteger('order_item_id');
            $table->boolean('is_stock_allocated')->default(false);
            $table->unsignedInteger('unallocated_quantity');
            $table->unsignedInteger('allocated_component_item_id');
            // ここから受注データの内容
            $table->unsignedInteger('ship_quantity');
            // ここまで受注データの内容
            $table->string('item_code_snapshot', 255)->nullable();
            $table->string('item_jan_code_snapshot', 255)->nullable();
            $table->string('item_name_snapshot', 255)->nullable();
            $table->boolean('is_stock_managed_snapshot')->nullable();
            $table->boolean('is_shipping_inspection_required_snapshot')->nullable();
            $table->boolean('is_hide_on_delivery_note_snapshot')->nullable();
            $table->timestamps();
            // 外部キー
            $table->foreign('order_item_id')->references('order_item_id')->on('order_items')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('allocated_component_item_id')->references('item_id')->on('items')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_components');
    }
};
