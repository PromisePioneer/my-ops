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
        Schema::create('item_distribution_records', function (Blueprint $table) {
            $table->id();
            $table->string('po_number');
            $table->date('date');
            $table->string('item_code');
            $table->string('item_name');
            $table->string('qty')->nullable();
            $table->string('from');
            $table->string('to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_distribution_records');
    }
};
