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
            $table->enum('type', ['ASET', 'JUAL']);
            $table->string('code')->nullable();
            $table->foreignId('category_id')
                ->constrained('item_categories')
                ->cascadeOnDelete();
            $table->foreignId('unit_type_id')
                ->constrained('unit_types')
                ->cascadeOnDelete();
            $table->enum('material', ['Besi', 'Non besi']);
            $table->double('reorder_level');
            $table->boolean('must_have_code')->default(false);
            $table->boolean('is_code_listed')->default(false);
            $table->timestamps();
            $table->softDeletes();
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
