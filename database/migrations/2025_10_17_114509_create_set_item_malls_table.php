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
        Schema::create('set_item_mall', function (Blueprint $table) {
            $table->increments('set_item_mall_id');
            $table->unsignedInteger('set_item_id');
            $table->unsignedInteger('mall_id');
            $table->string('mall_set_item_code', 255);
            $table->timestamps();
            // 外部キー
            $table->foreign('set_item_id')->references('set_item_id')->on('set_items')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('mall_id')->references('mall_id')->on('malls')->cascadeOnUpdate()->cascadeOnDelete();
            // ユニーク制約
            $table->unique(['set_item_id', 'mall_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_item_mall');
    }
};
