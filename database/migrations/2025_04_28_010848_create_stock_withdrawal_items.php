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
        Schema::create('stock_withdrawal_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_withdrawal_id')
                ->constrained('stock_withdrawals')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->date('date')->nullable();
            $table->foreignId('stock_id')
                ->constrained('stocks')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('code')->nullable();
            $table->double('qty');
            $table->enum('status', ['Dibawa', 'Dikembalikan', 'Terpakai', 'Habis'])->default('Dibawa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('stock_withdrawal_items');
    }
};
