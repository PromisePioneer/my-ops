<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserIdentityInformations extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_identity_informations', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('nik');
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->enum('gender', ['Laki Laki', 'Perempuan']);
            $table->enum('religion', ['Islam', 'Kristen', 'Hindu', 'Budha', 'Katholik', 'Konghucu']);
            $table->string('ktp_attachment');
            $table->enum('marital_status', ['Menikah', 'Tidak Menikah']);
            $table->enum('married_status',
                ['TK/0', 'TK/1', 'TK/2', 'TK/3', 'K/0', 'K/1', 'K/2', 'K/3'])->default('TK/0');
            $table->text('home_address')->nullable();
            $table->string('phone_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('user_identity_informations');
    }
}
