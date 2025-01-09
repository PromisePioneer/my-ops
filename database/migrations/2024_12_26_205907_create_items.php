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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained('item_categories')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('unit_type_id')->constrained('unit_types');
            $table->boolean('already_has_sn_on_item')->default(false);
            $table->boolean('need_sn')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('items');
    }
};
