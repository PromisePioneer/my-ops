<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number');
            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnDelete();
            $table->date('date');
            $table->foreignId('item_id')
                ->nullable()
                ->constrained('item_collections')
                ->cascadeOnDelete();
            $table->double('qty')->nullable();
            $table->enum('type', ['Default', 'Barang', 'Beban', 'Utang', 'Piutang']);
            $table->double('unit_price');
            $table->double('total_price');
            $table->text('detail');
            $table->foreignId('debit_account_id')
                ->constrained('accounts')
                ->cascadeOnDelete();
            $table->foreignId('credit_account_id')
                ->constrained('accounts')
                ->cascadeOnDelete();
            $table->boolean('locked_status')
                ->default(false);
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->enum('confirmation_status', ['Diterima', 'Revisi', 'Diproses', 'Ditolak'])
                ->default('Diproses');
            $table->foreignId('confirmed_by')
                ->nullable()
                ->constrained('users');
            $table->text('confirmation_excuses')
                ->nullable();
            $table->text('final_excuses')->nullable();
            $table->enum('final_status', ['Diterima', 'Ditolak', 'Pending'])->default('Pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
