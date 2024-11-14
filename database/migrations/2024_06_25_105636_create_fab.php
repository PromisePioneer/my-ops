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
            $table->foreignId('offering_letter_id')->nullable()->constrained('offering_letters');
            $table->foreignId('contact_id')->constrained('contacts');
            $table->string('fab_number');
            $table->string('contract_number');
            $table->string('date');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('pic')->constrained('users');
            $table->boolean('status')->default(false);
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
