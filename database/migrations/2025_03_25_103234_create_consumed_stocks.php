<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('consumed_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnDelete();
            $table->foreignId('stock_id')
                ->constrained('stocks')
                ->cascadeOnDelete();
            $table->foreignId('debit_account_id')
                ->constrained('accounts')
                ->cascadeOnDelete();
            $table->foreignId('credit_account_id')
                ->constrained('accounts')
                ->cascadeOnDelete();
            $table->string('qty');
            $table->foreignId('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consumed_stocks');
    }
};
