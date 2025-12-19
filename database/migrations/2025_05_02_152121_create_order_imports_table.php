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
        Schema::create('order_imports', function (Blueprint $table){
            $table->increments('order_import_id');
            $table->string('order_control_id', 16)->nullable();
            $table->date('order_import_date');
            $table->time('order_import_time');
            $table->unsignedInteger('order_status_id');
            $table->string('shipping_base_id', 10)->nullable();
            $table->date('desired_delivery_date')->nullable();
            $table->string('desired_delivery_time', 20)->nullable();
            // ここから受注データの内容
            $table->string('order_no', 50);
            $table->date('order_date');
            $table->time('order_time')->nullable();
            $table->string('buyer_name', 255)->nullable();
            $table->string('ship_name', 255);
            $table->string('ship_postal_code', 8);
            $table->string('ship_province_name', 10)->nullable();
            $table->string('ship_address', 255);
            $table->string('ship_tel', 15);
            $table->string('ship_id', 20)->nullable();
            $table->unsignedInteger('shipping_fee')->nullable();
            $table->unsignedInteger('payment_amount')->nullable();
            $table->text('order_message')->nullable();
            $table->string('order_item_system_code', 255)->nullable();
            $table->string('order_item_code', 255);
            $table->string('order_item_name', 500);
            $table->unsignedInteger('order_quantity');
            $table->unsignedInteger('order_item_price')->nullable();
            $table->string('mall_shipping_method', 50)->nullable();
            // ここまで受注データの内容
            $table->unsignedInteger('order_category_id');
            $table->string('order_control_id_seq', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_imports');
    }
};
