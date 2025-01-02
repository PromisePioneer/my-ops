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
        Schema::create('central_warehouse_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('central_warehouse_item_id')->constrained('central_warehouse_items');
            $table->string('sn')->unique();
            $table->boolean('status')->default(false);
            $table->string('code')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('central_warehouse_stocks');
    }
};
