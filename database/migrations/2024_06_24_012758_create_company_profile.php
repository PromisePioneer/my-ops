<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyProfile extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_profile', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('npwp');
            $table->string('bank');
            $table->string('bank_account_number');
            $table->string('bank_account_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('company_profile');
    }
}
