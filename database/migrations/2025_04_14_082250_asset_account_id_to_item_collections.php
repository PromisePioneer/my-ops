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
        Schema::table('item_collections', function (Blueprint $table) {
            $table->foreignId('asset_account_id')
                ->nullable()
                ->after('unit_type_id')
                ->constrained('accounts')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_collections', function (Blueprint $table) {
            //
        });
    }
};
