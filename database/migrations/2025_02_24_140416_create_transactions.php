<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('transaction_number')->nullable();
            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnDelete();
            $table->foreignId('contact_id')
                ->constrained('contacts')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->date('date');
            $table->foreignId('item_id')
                ->nullable()
                ->constrained('item_collections')
                ->cascadeOnDelete();
            $table->integer('qty')->nullable();
            $table->enum('type', ['Default', 'Barang', 'Beban', 'Utang', 'Piutang', 'Saldo Awal Persediaan']);
            $table->decimal('unit_price', 15, 4);
            $table->decimal('total_price', 15, 4);
            $table->text('detail');
            $table->string('attachment');
            $table->string('tax_invoice')->nullable();
            $table->foreignId('stock_account_id')
                ->nullable()
                ->constrained('accounts')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('debit_account_id')
                ->nullable()
                ->constrained('accounts')
                ->cascadeOnDelete();
            $table->foreignId('credit_account_id')
                ->nullable()
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
            $table->integer('qty_in_meter')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
