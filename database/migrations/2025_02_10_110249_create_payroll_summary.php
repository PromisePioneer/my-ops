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
        Schema::create('payroll_summary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->date('payroll_period');
            $table->integer('sick_count');
            $table->integer('permission_count');
            $table->integer('leave_count');
            $table->integer('not_checkout_count');
            $table->integer('not_checkin_count');
            $table->double('basic_salary');
            $table->double('position_allowance')->nullable();
            $table->double('overtime_allowance')->nullable();
            $table->double('transportation_allowance')->nullable();
            $table->double('meal_allowance')->nullable();
            $table->double('thr_allowance')->nullable();
            $table->double('sla_deduction')->nullable();
            $table->double('nine_past_fifteen_deduction')->nullable();
            $table->double('additional_deduction')->nullable();
            $table->double('marketing_bonus')->nullable();
            $table->double('sales_bonus')->nullable();
            $table->double('bonus_project')->nullable();
            $table->double('additional_bonus')->nullable();
            $table->double('total_allowance')->nullable();
            $table->double('total_deduction')->nullable();
            $table->double('total_bonus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_summary');
    }
};
