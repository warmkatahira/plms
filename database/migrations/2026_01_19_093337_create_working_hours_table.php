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
        Schema::create('working_hours', function (Blueprint $table) {
            $table->increments('working_hour_id');
            $table->string('working_type', 5);
            $table->decimal('working_hour', 4, 2);
            $table->timestamps();
            // 複合UNIQUE制約
            $table->unique(['working_type', 'working_hour'], 'working_hours_type_hour_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('working_hours');
    }
};
