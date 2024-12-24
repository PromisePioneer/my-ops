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
        Schema::create('list_of_items', function (Blueprint $table) {
            $table->id();
            $table->string('sn');
            $table->string('name');
            $table->date('date');
            $table->double('unit_price');
            $table->integer('qty');
            $table->double('shipping_cost');
            $table->double('ppn');
            $table->double('total_price');
            $table->foreignId('supplier_id')->nullable()
                ->constrained('suppliers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('travel_letter_receipt');
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
