<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_services', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->text('description');
            $table->integer('qty');
            $table->double('unit_price');
            $table->double('total_price');
            $table->timestamps();

            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices')
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
        Schema::dropIfExists('invoice_services');
    }
}
