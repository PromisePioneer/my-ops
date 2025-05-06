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
            $table->foreignId('supplier_id')
                ->constrained('suppliers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->date('date');
            $table->foreignId('item_id')
                ->nullable()
                ->constrained('item_collections')
                ->cascadeOnDelete();
            $table->double('qty')->nullable();
            $table->enum('type', ['Default', 'Barang', 'Beban', 'Utang', 'Piutang']);
            $table->decimal('unit_price', 15, 4);
            $table->decimal('total_price', 15, 4);
            $table->text('detail');
            $table->string('attachment');
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
            $table->enum('status', ['Diterima', 'Revisi', 'Ditolak', 'Diproses'])
                ->default('Diproses');
            $table->string('final_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
