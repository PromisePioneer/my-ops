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
        Schema::table('fab', function (Blueprint $table) {
            $table->foreignId('po_id')->constrained('purchase_orders')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->dropForeign(['offering_letter_id']);
            $table->dropColumn('offering_letter_id');
            $table->dropColumn('file_po');
            $table->dropForeign(['contact_id']);
            $table->dropColumn('contact_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
    }
};
