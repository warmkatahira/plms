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
        Schema::create('set_items', function (Blueprint $table){
            $table->increments('set_item_id');
            $table->string('set_item_code', 255)->unique();
            $table->string('set_item_name', 255);
            $table->string('set_item_image_file_name', 50)->default('no_image.png');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_items');
    }
};
