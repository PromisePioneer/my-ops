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
        Schema::create('cut_off_payroll_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('attendance_period_start')->default(28);
            $table->integer('attendance_period_end')->default(28);
            $table->integer('payroll_period_start')->default(28);
            $table->integer('payroll_period_end')->default(28);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cut_off_payroll_setting');
    }
};
