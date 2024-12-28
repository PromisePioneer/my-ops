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
        Schema::create('central_warehouse_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('sn')->unique();
            $table->foreignId('po_items_id')->constrained('po_list_of_items')
                ->cascadeOnDelete()
                ->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('item_categories')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('name');
            $table->string('merk')->nullable();
            $table->integer('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('central_warehouse_stocks');
    }
};
