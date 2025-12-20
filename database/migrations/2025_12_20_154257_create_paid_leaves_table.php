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
        Schema::create('paid_leaves', function (Blueprint $table) {
            $table->increments('paid_leave_id');
            $table->unsignedInteger('user_no');
            $table->decimal('paid_leave_granted_days', 4, 1);
            $table->decimal('paid_leave_remaining_days', 4, 1);
            $table->decimal('paid_leave_used_days', 4, 1);
            $table->decimal('daily_working_hours', 4, 2);
            $table->decimal('half_day_working_hours', 4, 2);
            $table->timestamps();
            // 外部キー
            $table->foreign('user_no')->references('user_no')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paid_leaves');
    }
};
