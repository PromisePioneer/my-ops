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
        Schema::create('initial_inventory_balance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->date('date');
            $table->foreignId('contact_id')
                ->constrained('contacts')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('item_id')
                ->constrained('item_collections')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('stock_account_id')
                ->constrained('accounts')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->double('qty');
            $table->decimal('unit_price', 15, 4);
            $table->decimal('total_price', 15, 4);
            $table->text('detail');
            $table->double('qty_in_meter')->nullable();
            $table->string('attachment');
            $table->boolean('status')->default(0);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('initial_inventory_balance');
    }
};
