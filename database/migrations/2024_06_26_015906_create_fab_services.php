<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('fab_has_service_categories', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('fab_id')
                ->constrained('fab')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('service_category_id')
                ->constrained('services_categories')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->integer('qty');
            $table->double('unit_price');
            $table->double('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('fab_has_service_categories');
    }
}
