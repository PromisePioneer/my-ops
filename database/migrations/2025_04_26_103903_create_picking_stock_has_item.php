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
        Schema::create('picking_stock_has_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('picking_stock_id')
                ->constrained('picking_stocks')
                ->cascadeOnDelete();
            $table->foreignId('stock_id')
                ->constrained('stocks')
                ->cascadeOnDelete();
            $table->double('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picking_stock_has_item');
    }
};
