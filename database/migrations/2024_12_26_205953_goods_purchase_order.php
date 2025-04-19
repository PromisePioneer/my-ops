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
        Schema::create('item_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses');
            $table->string('po_number')->unique();
            $table->string('invoice_number')->unique();
            $table->foreignId('item_id')
                ->constrained('item_collections')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->date('date');
            $table->double('unit_price');
            $table->integer('qty');
            $table->double('shipping_cost')->nullable();
            $table->double('ppn')->nullable();
            $table->double('total_price');
            $table->foreignId('supplier_id')->nullable()
                ->constrained('suppliers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->double('length_in_meter')->nullable();
            $table->string('travel_letter_receipt');
            $table->boolean('status_send')->default(false);
            $table->boolean('status_received')->default(false);
            $table->foreignId('send_by')->nullable()->constrained('users');
            $table->foreignId('received_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('list_of_items');
    }
};
