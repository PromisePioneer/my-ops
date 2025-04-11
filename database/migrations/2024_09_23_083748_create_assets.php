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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->foreignId('debit_account_id')->nullable()->constrained('accounts');
            $table->foreignId('credit_account_id')->nullable()->constrained('accounts');
            $table->date('date_received');
            $table->string('name');
            $table->integer('unit');
            $table->integer('useful_life')->nullable();
            $table->decimal('price_per_unit', 15, 4);
            $table->double('residu')->nullable();
            $table->decimal('total_price', 15, 4)->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('assets');
    }
};
