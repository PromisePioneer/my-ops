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
        Schema::create('fab_skl', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fab_id')
                ->nullable()
                ->constrained('fab')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('skl_id')
                ->nullable()
                ->constrained('skl')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fab_skl');
    }
};
