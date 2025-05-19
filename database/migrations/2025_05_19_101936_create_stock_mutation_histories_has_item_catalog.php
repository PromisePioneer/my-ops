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
        Schema::create('mutation_histories_item_catalogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mutation_histories_id')
                ->constrained('mutation_histories')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('item_catalog_id')
                ->constrained('item_catalogs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_mutatation_histories_has_item_catalog');
    }
};
