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
        Schema::create('items', function (Blueprint $table){
            $table->increments('item_id');
            $table->string('item_code', 255)->unique();
            $table->string('item_jan_code', 13)->nullable();
            $table->string('item_name', 255);
            $table->string('item_category_1', 100)->nullable();
            $table->string('item_category_2', 100)->nullable();
            $table->boolean('is_stock_managed');
            $table->boolean('is_shipping_inspection_required')->default(true);
            $table->boolean('is_hide_on_delivery_note')->default(false);
            $table->string('item_image_file_name', 50)->default('no_image.png');
            $table->unsignedInteger('sort_order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
