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
        Schema::create('order_import_patterns', function (Blueprint $table) {
            $table->increments('order_import_pattern_id');
            $table->string('pattern_name', 50)->unique();
            $table->string('pattern_description', 50)->nullable();
            $table->string('column_get_method', 10);
            $table->unsignedInteger('order_category_id');
            $table->timestamps();
            // 外部キー
            $table->foreign('order_category_id')->references('order_category_id')->on('order_categories')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_import_patterns');
    }
};
