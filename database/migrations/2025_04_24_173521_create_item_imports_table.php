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
        Schema::create('item_imports', function (Blueprint $table){
            $table->increments('item_import_id');
            $table->string('item_code', 255);
            $table->string('item_jan_code', 13)->nullable();
            $table->string('item_name', 255)->nullable();
            $table->string('item_category_1', 100)->nullable();
            $table->string('item_category_2', 100)->nullable();
            $table->boolean('is_stock_managed')->nullable();
            $table->boolean('is_shipping_inspection_required')->nullable();
            $table->boolean('is_hide_on_delivery_note')->nullable();
            $table->unsignedInteger('sort_order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_imports');
    }
};
