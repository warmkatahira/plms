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
        Schema::create('item_mall', function (Blueprint $table) {
            $table->increments('item_mall_id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('mall_id');
            $table->string('mall_item_code', 255);
            $table->string('mall_variation_code', 255)->nullable();
            $table->timestamps();
            // 外部キー
            $table->foreign('item_id')->references('item_id')->on('items')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('mall_id')->references('mall_id')->on('malls')->cascadeOnUpdate()->cascadeOnDelete();
            // ユニーク制約
            $table->unique(['item_id', 'mall_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_mall');
    }
};
