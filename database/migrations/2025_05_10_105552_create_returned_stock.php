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
        Schema::create('returned_stock', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('stock_withdrawal_id')
                ->constrained('stock_withdrawals')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('stock_id')
                ->constrained('stocks')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('code')->nullable();
            $table->integer('qty');
            $table->foreignId('image');
            $table->foreignId('returned_by')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returned_stock');
    }
};
