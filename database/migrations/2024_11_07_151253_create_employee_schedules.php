<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_time_id')->constrained('work_time');
            $table->integer('employee_id');
            $table->boolean('is_cross_midnight_shift')->default(false);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['H', 'L'])->default('H');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_shchedules');
    }
};
