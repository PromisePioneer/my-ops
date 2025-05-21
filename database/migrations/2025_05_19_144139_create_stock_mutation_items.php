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
        Schema::create('stock_mutation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_mutation_id')
                ->constrained('stock_mutations')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('stock_id')
                ->constrained('stocks')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('code')->nullable();
            $table->double('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutation_has_stocks');
    }
};
