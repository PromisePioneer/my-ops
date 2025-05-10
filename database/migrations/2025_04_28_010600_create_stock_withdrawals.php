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
        Schema::create('stock_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->date('date');
            $table->string('description');
            $table->foreignId('pic_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('stocker_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->enum('status', ['Pending', 'Dibawa', 'Dikembalikan'])->default('Pending');
            $table->string('stocker_signature_after_withdraw')->nullable();
            $table->string('pic_signature_after_withdraw')->nullable();
            $table->string('stocker_signature_after_returned')->nullable();
            $table->string('pic_signature_after_returned')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('stock_withdrawals');
    }
};
