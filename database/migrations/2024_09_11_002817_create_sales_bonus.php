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
        Schema::create('sales_bonus', function (Blueprint $table) {
            $table->id();
            $table->date('date_active');
            $table->string('customer_name');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('packet_id')->constrained('broadband_packets');
            $table->integer('discount')->nullable();
            $table->double('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_bonus');
    }
};
