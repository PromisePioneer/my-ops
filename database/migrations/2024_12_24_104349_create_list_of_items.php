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
            $table->foreignId('po_items_id')->constrained('po_list_of_items')
                ->cascadeOnDelete()
                ->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('inventory_categories')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('name');
            $table->integer('qty');
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
