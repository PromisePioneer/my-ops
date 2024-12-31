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
        Schema::create('po_list_of_items', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->string('invoice_number')->unique();
            $table->foreignId('item_id')
                ->constrained('items')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->date('date');
            $table->double('unit_price');
            $table->integer('qty');
            $table->foreignId('unit_type_id')->constrained('unit_types')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->double('shipping_cost')->nullable();
            $table->double('ppn')->nullable();
            $table->double('total_price');
            $table->foreignId('supplier_id')->nullable()
                ->constrained('suppliers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('travel_letter_receipt');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_of_items');
    }
};
