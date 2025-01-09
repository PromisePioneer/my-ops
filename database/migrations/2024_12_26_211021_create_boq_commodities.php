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
        Schema::create('boq_commodities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boq_id')->constrained('boq')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('item_id')
                ->constrained('items')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('merk')->nullable();
            $table->string('qty');
            $table->double('unit_price');
            $table->double('total_price');
            $table->date('used_estimation');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('boq_commodities');
    }
};
