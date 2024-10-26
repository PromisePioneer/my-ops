<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferingLetterProductServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('offering_letter_product_services', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('offering_letter_id')->constrained('offering_letters')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('service_category_id')->constrained('services_categories')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->double('price');
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
        Schema::dropIfExists('offering_letter_product_services');
    }
}
