<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBastProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bast_products', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bast_id');
            $table->string('product_name');
            $table->string('qty');
            $table->string('serial_number');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('bast_id')
                ->references('id')
                ->on('bast')
                ->onUpdate('cascade')
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
        Schema::dropIfExists('bast_product');
    }
}
