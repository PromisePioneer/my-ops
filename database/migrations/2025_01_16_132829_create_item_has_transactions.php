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
        Schema::create('item_has_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_transaction_id')
                ->constrained('item_transactions')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('item_id')->constrained('item_collections')
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
