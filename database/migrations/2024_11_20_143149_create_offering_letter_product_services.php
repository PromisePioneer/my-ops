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
        Schema::create('offering_letter_product_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offering_letter_id')
                ->constrained('offering_letters')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('service_category_id')
                ->constrained('services_categories')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('unit_type_id')->constrained('unit_types');
            $table->integer('capacity')->nullable();
            $table->double('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offering_letter_product_services');
    }
};
