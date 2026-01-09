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
        Schema::create('statutory_leaves', function (Blueprint $table) {
            $table->increments('paid_leave_id');
            $table->unsignedInteger('user_no');
            $table->date('statutory_leave_expiration_date')->nullable();
            $table->decimal('statutory_leave_days', 4, 1);
            $table->decimal('statutory_leave_remaining_days', 4, 1);
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
        Schema::dropIfExists('statutory_leaves');
    }
};
