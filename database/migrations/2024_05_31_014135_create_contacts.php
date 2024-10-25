<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContacts extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contacts', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->string('pic_name');
            $table->string('company_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone_number');
            $table->enum('identity_type', ['ktp', 'sim', 'passport'])->nullable();
            $table->string('identity_number')->nullable();
            $table->string('fax')->nullable();
            $table->string('npwp')->nullable();
            $table->text('complete_address')->nullable();
            $table->text('other_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
}
