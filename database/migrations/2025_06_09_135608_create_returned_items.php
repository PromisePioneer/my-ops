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
        Schema::create('returned_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_withdrawal_item_id')
                ->constrained('stock_withdrawal_items')
                ->cascadeOnDelete();
            $table->enum('status', ['Sisa', 'Dikembalikan', 'Habis']);
            $table->double('remaining_qty')->nullable()->default(0);
            $table->enum('item_condition', ['Ada Rusak', 'Bagus Semua', 'Habis', 'Baik', 'Rusak']);
            $table->double('broken_qty')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returned_items');
    }
};
