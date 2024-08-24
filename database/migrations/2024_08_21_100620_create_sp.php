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
        Schema::create('sp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->foreignId('user_id')->constrained('users');
            $table->string('sp_number');
            $table->date('sp_date');
            $table->enum('sp_type', ['SP-1', 'SP-2', 'SP-3']);
            $table->string('reason');
            $table->foreignId('punished_by')->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sp');
    }
};
