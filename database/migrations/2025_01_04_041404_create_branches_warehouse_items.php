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
        Schema::create('branch_warehouse_items', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('po_item_id')->constrained('po_list_of_items');
            $table->foreignId('central_warehouse_stock_id')->nullable()
                ->constrained('central_warehouse_stocks')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('item_id')->constrained('items')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->integer('qty');
            $table->boolean('status')->default(false);
            $table->foreignId('accepted_by')->nullable()->constrained('users');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('branches_warehouse_items');
    }
};
