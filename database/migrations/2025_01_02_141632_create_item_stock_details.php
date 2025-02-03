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
        Schema::create('goods_stock_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('goods_purchase_order');
            $table->foreignId('stock_id')->constrained('goods_stock');
            $table->foreignId('item_id')->constrained('goods')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('item_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_stock_details');
    }
};
