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
        Schema::table('fab_services', function (Blueprint $table) {
            $table->foreignId('unit_type_id')->after('capacity')->constrained('unit_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fab_services', function (Blueprint $table) {
            //
        });
    }
};
