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
        Schema::create('item_catalogs', function (Blueprint $table) {
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
            $table->foreignId('draft_stock_id')
                ->constrained('draft_stocks')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('stock_id')
                ->constrained('stocks')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('item_id')->constrained('item_collections')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('asset_id')->nullable()->constrained('assets')->cascadeOnDelete();
            $table->double('qty_in_meter')->nullable();
            $table->string('code');
            $table->enum('condition', ['Rusak', 'Baik', 'Diperbaiki']);
            $table->enum('status', ['Tersedia', 'Terpakai', 'Dibawa'])->default('Tersedia');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_catalog');
    }
};
