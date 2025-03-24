<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('account_transactions', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')
                ->nullable()
                ->constrained('transactions')
                ->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->date('date');
            $table->foreignId('account_id')->nullable()->constrained('accounts');
            $table->enum('transaction_type', ['TR', 'SA'])->default('TR');
            $table->string('description')->nullable();
            $table->enum('entries_type', ['debit', 'credit'])->nullable();
            $table->double('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('account_transactions');
    }
}
