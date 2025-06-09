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
        Schema::create('draft_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')
                ->nullable()
                ->constrained('transactions')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('initial_balance_inventory_id')
                ->nullable()
                ->constrained('initial_inventory_balance')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->double('qty');
            $table->double('qty_in_meter')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draft_items');
    }
};
