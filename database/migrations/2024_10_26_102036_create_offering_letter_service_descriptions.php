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
        Schema::create('offering_letter_service_descriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offering_letter_id')->constrained('offering_letters');
            $table->string('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offering_letter_service_descriptions');
    }
};
