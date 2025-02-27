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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number');
            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnDelete();
            $table->date('date');
            $table->double('qty');
            $table->foreignId('unit_type_id')
                ->constrained('unit_types')
                ->cascadeOnDelete();
            $table->double('unit_price');
            $table->double('total_price');
            $table->text('detail');
            $table->foreignId('debit_account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('credit_account_id')->constrained('accounts')->cascadeOnDelete();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
