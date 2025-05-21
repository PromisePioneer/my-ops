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
        Schema::create('stock_mutations', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('old_branch_id')
                ->constrained('branches')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('new_branch_id')
                ->constrained('branches')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('sender_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();
            $table->foreignId('receiver_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();
            $table->text('description');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_mutation_histories');
    }
};
