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
        Schema::create('order_import_pattern_details', function (Blueprint $table) {
            $table->increments('order_import_pattern_detail_id');
            $table->unsignedInteger('order_import_pattern_id');
            $table->string('system_column_name', 100);
            $table->string('order_column_name', 255)->nullable();
            $table->string('order_column_index', 50)->nullable();
            $table->string('fixed_value', 255)->nullable();
            $table->timestamps();
            // 外部キー
            $table->foreign('order_import_pattern_id')->references('order_import_pattern_id')->on('order_import_patterns')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_import_pattern_details');
    }
};
