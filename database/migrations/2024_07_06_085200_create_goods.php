<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('goods', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('unit_type_id');
            $table->string('serial_number')->unique();
            $table->string('name');
            $table->double('unit_price');
            $table->double('total_price');
            $table->integer('qty');
            $table->string('file');
            $table->boolean('confirmation_status')->default(0);
            $table->enum('type', ['aset', 'bukan aset']);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('unit_type_id')->references('id')->on('unit_types');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('goods');
    }
}
