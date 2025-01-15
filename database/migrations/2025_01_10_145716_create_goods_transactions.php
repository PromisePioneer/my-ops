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
        Schema::create('goods_transaction', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('po_id')->constrained('goods_purchase_order');
            $table->foreignId('item_id')->constrained('goods');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses');
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->enum('type', ['in', 'out']);
            $table->integer('qty');
            $table->string('notes')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('from_po')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_transactions');
    }
};
