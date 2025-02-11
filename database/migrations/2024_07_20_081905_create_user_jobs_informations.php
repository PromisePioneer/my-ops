<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserJobsInformations extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_jobs_informations', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->double('fixed_salary')->nullable();
            $table->double('position_allowance')->nullable();
            $table->enum('week_holiday',
                [
                    'Senin',
                    'Selasa',
                    'Rabu',
                    'Kamis',
                    'Jumat',
                    'Sabtu',
                    'Minggu',
                ])->default('minggu');
            $table->enum('contract_status',
                [
                    'Tetap',
                    'Kontrak',
                    'Vendor',
                    'Training',
                    'Magang',
                    'Freelance',
                    'Non Karyawan',
            ]);
            $table->string('bank_account_number')->nullable();
            $table->enum('bpjs_kes', ['ya', 'tidak'])->default('tidak')->nullable();
            $table->string('no_kpj')->nullable();
            $table->enum('bpjs_ket', ['ya', 'tidak'])->default('tidak')->nullable();
            $table->string('no_kis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('user_jobs_informations');
    }
}
