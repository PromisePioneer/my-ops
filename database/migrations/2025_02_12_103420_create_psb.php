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
        Schema::create('psb', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->date('registration_date');
            $table->date('active_date');
            $table->json('technician');
            $table->string('phone_number');
            $table->string('last_pay');
            $table->string('address');
            $table->foreignId('area_id')
                ->constrained('areas')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('pic')
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psb');
    }
};
