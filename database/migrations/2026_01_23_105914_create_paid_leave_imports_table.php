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
        Schema::create('paid_leave_imports', function (Blueprint $table) {
            $table->increments('statutory_leave_import_id');
            $table->string('employee_no', 4);
            $table->decimal('paid_leave_granted_days', 4, 1);
            $table->decimal('paid_leave_used_days', 4, 1);
            $table->decimal('paid_leave_remaining_days', 4, 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paid_leave_imports');
    }
};
