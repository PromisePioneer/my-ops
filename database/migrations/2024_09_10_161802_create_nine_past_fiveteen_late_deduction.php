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
        Schema::create('nine_past_fiveteen_late_deduction', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('kca_id')->constrained('users');
            $table->foreignId('technician_id')->constrained('users');
            $table->integer('total_amount_of_late');
            $table->double('total_deduction_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('sla_deduction');
    }
};
