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
        Schema::create('goods_has_transaction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_transaction_id')
                ->constrained('goods_transaction')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('item_id')->constrained('goods')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_has_transactions');
    }
};
