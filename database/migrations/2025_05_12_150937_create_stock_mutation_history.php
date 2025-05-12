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
        Schema::create('stock_mutation_histories', function (Blueprint $table) {
            $table->id();

            //old
            $table->foreignId('old_branch_id')
                ->constrained('branches')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();;
            $table->foreignId('old_stock_id')
                ->constrained('stocks')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('new_branch_id')
                ->constrained('branches')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('new_stock_id')
                ->constrained('stocks')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('description', 255);
            $table->foreignId('debit_account_id')
                ->nullable()
                ->constrained('accounts')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('credit_account_id')
                ->nullable()
                ->constrained('accounts')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->integer('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_mutation_history');
    }
};
