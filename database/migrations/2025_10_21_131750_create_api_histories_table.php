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
        Schema::create('api_histories', function (Blueprint $table) {
            $table->increments('api_history_id');
            $table->unsignedInteger('mall_id');
            $table->unsignedInteger('api_action_id');
            $table->unsignedInteger('api_status_id');
            $table->string('error_file_name', 255)->nullable();
            $table->timestamps();
            // 外部キー
            $table->foreign('mall_id')->references('mall_id')->on('malls')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('api_action_id')->references('api_action_id')->on('api_actions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('api_status_id')->references('api_status_id')->on('api_statuses')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_histories');
    }
};
