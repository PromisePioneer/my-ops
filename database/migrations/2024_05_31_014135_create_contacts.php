<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('contacts', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('full_name');
            $table->string('company_name')->nullable();
            $table->string('email');
            $table->string('phone_number');
            $table->enum('identity_type', ['ktp','sim','passport']);
            $table->string('identity_number');
            $table->string('fax')->nullable();
            $table->string('npwp');
            $table->text('complete_address');
            $table->text('other_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
}
