<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFab extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fab', static function (Blueprint $table) {
            $table->id();
            $table->string('fab_number');
            $table->string('date');
            $table->enum('subscription_period', ['1 Tahun', '2 Tahun', 'Sesuai Kontrak']);
            $table->foreignId('contact_id')->constrained('contacts');
            $table->foreignId('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('fab');
    }
}
