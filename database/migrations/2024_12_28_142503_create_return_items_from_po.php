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
        Schema::create('return_items_from_po', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')
                ->constrained('item_purchase_orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('item_collections')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->integer('qty');
            $table->string('reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('return_items_from_po');
    }
};
