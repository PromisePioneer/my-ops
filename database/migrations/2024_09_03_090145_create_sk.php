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
        Schema::create('sk', callback: function (Blueprint $table) {
            $table->id();
            $table->string('sk_number');
            $table->date('date');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('sk_type', ['Promosi', 'Demosi', 'Mutasi']);
            $table->foreignId('old_branch_id')->nullable()->constrained('branches');
            $table->foreignId('new_branch_id')->constrained('branches');
            $table->foreignId('old_role_id')->nullable()->constrained('roles');
            $table->foreignId('new_role_id')->constrained('roles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sk');
    }
};
