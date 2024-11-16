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
        Schema::create('spk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('baa_id')->constrained('baa');
            $table->string('spk_number');
            $table->string('name');
            $table->date('date');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('from')->constrained('users');
            $table->foreignId('to')->constrained('users');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk');
    }
};
