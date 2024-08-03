<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPayroll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('user_payroll', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->date('period_start');
            $table->date('period_end');
            $table->date('salary_date');
            $table->double('positional_allowance')->nullable();
            $table->double('meal_allowance')->nullable();
            $table->double('transportation_allowance')->nullable();
            $table->double('overtime_allowance')->nullable();
            $table->double('sales_bonus')->nullable();
            $table->double('project_bonus')->nullable();
            $table->double('other_bonus')->nullable();
            $table->double('bpjs_tek_dues')->nullable();
            $table->double('bpjs_kes_dues')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_payroll');
    }
}
