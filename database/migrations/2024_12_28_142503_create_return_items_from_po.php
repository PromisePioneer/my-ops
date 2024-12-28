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
                ->constrained('po_list_of_items')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('item_categories');
            $table->foreignId('item_id')->constrained('items')
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
        Schema::dropIfExists('return_items_from_po');
    }
};
