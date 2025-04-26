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
        Schema::create('picking_stocks', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->boolean('approved_by_kca')->default(0);
            $table->boolean('approved_by_stocker')->default(0);
            $table->foreignId('kca_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('stocker_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_take');
    }
};
