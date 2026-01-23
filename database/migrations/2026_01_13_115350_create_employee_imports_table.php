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
        Schema::create('employee_imports', function (Blueprint $table) {
            $table->increments('employee_import_id');
            $table->boolean('status')->default(true);
            $table->string('short_base_name', 10)->nullable();
            $table->string('employee_no', 4);
            $table->string('user_name', 20)->nullable();
            $table->string('user_id', 20)->nullable();
            $table->string('password', 255)->nullable();
            $table->decimal('paid_leave_granted_days', 4, 1)->nullable();
            $table->decimal('paid_leave_remaining_days', 4, 1)->nullable();
            $table->decimal('paid_leave_used_days', 4, 1)->nullable();
            $table->decimal('daily_working_hours', 4, 2)->nullable();
            $table->decimal('half_day_working_hours', 4, 2)->nullable();
            $table->boolean('is_auto_update_statutory_leave_remaining_days')->default(false);
            $table->date('statutory_leave_expiration_date')->nullable();
            $table->decimal('statutory_leave_days', 4, 1)->nullable();
            $table->decimal('statutory_leave_remaining_days', 4, 1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_imports');
    }
};
