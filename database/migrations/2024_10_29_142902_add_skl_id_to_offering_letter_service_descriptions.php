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
            $table->foreignId('skl_id')->nullable()->after('offering_letter_id')
                ->constrained('skl')
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
