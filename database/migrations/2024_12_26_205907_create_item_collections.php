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
        Schema::create('item_collections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')
                ->constrained('item_categories')
                ->cascadeOnDelete();
            $table->foreignId('unit_type_id')
                ->constrained('unit_types')
                ->cascadeOnDelete();
            $table->enum('material', ['Besi', 'Non besi']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('item_collections');
    }
};
