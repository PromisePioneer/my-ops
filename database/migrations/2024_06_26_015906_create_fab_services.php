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
    public function up()
    {
        Schema::create('fab_services', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fab_id');
            $table->unsignedBigInteger('service_category_id');
            $table->integer('qty');
            $table->double('unit_price');
            $table->double('total_price');
            $table->timestamps();

            $table->foreign('fab_id')
                ->references('id')
                ->on('fab')
                ->onDelete('cascade');

            $table->foreign('service_category_id')
                ->references('id')
                ->on('services_categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fab_services');
    }
}
