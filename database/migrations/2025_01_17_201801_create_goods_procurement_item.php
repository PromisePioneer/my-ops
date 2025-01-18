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
        Schema::create('goods_request_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_request_id')->constrained('goods_request');
            $table->foreignId('item_id')->constrained('goods');
            $table->integer('required_qty');
            $table->integer('qty_requested');
            $table->text('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('goods_procurement_item');
    }
};
