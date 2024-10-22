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
        Schema::create('boq_timeline_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boq_id')->constrained('boq')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');
            $table->integer('qty')->nullable();
            $table->foreignId('unit_type_id')->nullable()->constrained('unit_types');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('pic')->nullable()->constrained('users');
            $table->integer('technician');
            $table->boolean('finished')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boq_timeline_project');
    }
};
