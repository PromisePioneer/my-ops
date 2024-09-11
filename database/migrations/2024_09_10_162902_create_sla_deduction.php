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
        Schema::create('sla_deduction', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('kca_id')->constrained('users');
            $table->foreignId('technician_id')->constrained('users');
            $table->double('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sla_deduction');
    }
};
