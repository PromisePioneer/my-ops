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
        Schema::create('odp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('odp_areas');
            $table->string('name')->unique();
            $table->enum('classification', ['AS', 'Turunan']);
            $table->enum('passive_splitter', ['ODP', 'FAT', 'ODU']);
            $table->double('lat', 10, 8);
            $table->double('long');
            $table->integer('max_capacity');
            $table->integer('used_capacity');
            $table->string('cut_off');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('odp');
    }
};
