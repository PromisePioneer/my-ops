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
        Schema::create('boq', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable();
            $table->string('boq_number')->unique();
            $table->string('title');
            $table->date('date');
            $table->enum('approved_by_project_controller', ['Pending', 'Diterima', 'Ditolak', 'Revisi'])->default(
                'Pending'
            );
            $table->foreignId('submitter')->constrained('users');
            $table->boolean('approved_by_operational_manager')->default(false);
            $table->boolean('known_by_director')->default(false);
            $table->boolean('known_by_gm')->default(false);
            $table->string('attachment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boq');
    }
};
