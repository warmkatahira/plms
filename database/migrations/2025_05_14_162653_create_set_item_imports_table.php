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
        Schema::create('set_item_imports', function (Blueprint $table){
            $table->increments('set_item_import_id');
            $table->string('set_item_code', 255);
            $table->string('set_item_name', 255);
            $table->string('component_item_code', 255);
            $table->unsignedInteger('component_item_id')->nullable();
            $table->unsignedInteger('component_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_item_imports');
    }
};
