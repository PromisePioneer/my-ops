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
        Schema::table('offering_letter_service_descriptions', function (Blueprint $table) {
            $table->foreignId('skl_id')->after('offering_letter_id')
                ->constrained('offering_letter_skl')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offering_letter_service_descriptions', function (Blueprint $table) {
            //
        });
    }
};
