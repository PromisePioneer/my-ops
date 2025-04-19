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
        Schema::create('item_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('from_warehouse_id')
                ->nullable()
                ->constrained('warehouses')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('to_warehouse_id')
                ->nullable()
                ->constrained('warehouses')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();;
            $table->foreignId('from_branch_id')
                ->nullable()
                ->constrained('branches')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('to_branch_id')
                ->nullable()
                ->constrained('branches')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->boolean('status_send')->default(false);
            $table->boolean('status_received')->default(false);
            $table->foreignId('sent_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('received_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->boolean('from_po')->default(false);
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_transactions');
    }
};
