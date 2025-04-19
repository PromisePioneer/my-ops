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
        Schema::create('transaction_goods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_transaction_id')->constrained('item_transactions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('item_collections')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_goods');
    }
};
