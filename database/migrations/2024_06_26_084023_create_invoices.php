<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('invoices', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('branch_id');
            $table->string('invoice_number')->unique();
            $table->unsignedBigInteger('account_id');
            $table->date('due_date');
            $table->text('description')->nullable();
            $table->string('baa_file');
            $table->string('cooperative_contract_file');
            $table->double('grand_total');
            $table->enum('payment_status', ['Lunas', 'Belum Lunas'])->default('Belum Lunas');
            $table->boolean('status')->default('0');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();


            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
                ->onDelete('cascade');

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onDelete('cascade');

            $table->foreign('contact_id')
                ->references('id')
                ->on('contacts')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
}
