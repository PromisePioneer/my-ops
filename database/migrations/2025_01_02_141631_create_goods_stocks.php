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
        Schema::create('goods_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('goods_purchase_order');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses');
            $table->foreignId('branch_id')->nullable()->constrained('warehouses');
            $table->foreignId('item_id')->constrained('goods');
            $table->integer('qty')->nullable();
            $table->string('sn')->nullable();
            $table->boolean('status')->default(false);
            $table->enum('condition', ['Baik', 'Rusak Ringan', 'Rusak Berat'])->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('goods_stock');
    }
};
