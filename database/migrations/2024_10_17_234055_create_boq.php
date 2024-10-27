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
            $table->foreignId('submitter_id')->constrained('users');
            $table->enum(
                'operational_manager_approval',
                ['Diterima', 'Revisi', 'Ditolak', 'Pending']
            )->default('Pending');
            $table->string('reason')->nullable();
            $table->foreignId('operational_manager_id')->nullable()->constrained('users');
            $table->boolean('known_by_director')->default(false);
            $table->foreignId('director_id')->nullable()->constrained('users');
            $table->boolean('known_by_gm')->default(false);
            $table->foreignId('gm_id')->nullable()->constrained('users');
            $table->string('attachment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('boq');
    }
};
