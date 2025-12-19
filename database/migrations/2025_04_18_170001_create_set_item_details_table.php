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
        Schema::create('set_item_details', function (Blueprint $table){
            $table->increments('set_item_detail_id');
            $table->unsignedInteger('set_item_id');
            $table->unsignedInteger('component_item_id');
            $table->unsignedInteger('component_quantity');
            $table->timestamps();
            // 外部キー
            $table->foreign('set_item_id')->references('set_item_id')->on('set_items')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('component_item_id')->references('item_id')->on('items')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_item_details');
    }
};
