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
        Schema::create('fab_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fab_id')
                ->constrained('fab')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('service_category_id')
                ->constrained('service_categories')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->integer('capacity')->nullable();
            $table->foreignId('unit_type_id')->constrained('unit_types')->cascadeOnDelete()->cascadeOnUpdate();
            $table->double('price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('fab_services');
    }
};
