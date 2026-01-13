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
            $table->boolean('status');
            $table->string('short_base_name', 10);
            $table->string('employee_no', 4);
            $table->string('user_name', 20);
            $table->string('user_id', 20);
            $table->decimal('paid_leave_granted_days', 4, 1);
            $table->decimal('paid_leave_remaining_days', 4, 1);
            $table->decimal('paid_leave_used_days', 4, 1);
            $table->decimal('daily_working_hours', 4, 2);
            $table->decimal('half_day_working_hours', 4, 2);
            $table->boolean('is_auto_update_statutory_leave_remaining_days');
            $table->date('statutory_leave_expiration_date');
            $table->decimal('statutory_leave_days', 4, 1);
            $table->decimal('statutory_leave_remaining_days', 4, 1);
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
