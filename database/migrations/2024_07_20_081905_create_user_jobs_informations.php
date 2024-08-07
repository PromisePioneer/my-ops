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
            $table->string('emp_code');
            $table->string('absent_id');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('department_id')->constrained('departments');
            $table->date('join_date');
            $table->double('fixed_salary');
            $table->enum('contract_status', [
                'Tetap', 'Kontrak', 'Vendor', 'Training', 'Magang', 'Freelance', 'Non Karyawan',
            ]);
            $table->string('bank_account_number');
            $table->enum('bpjs_kes', ['ya', 'tidak'])->default('tidak');
            $table->string('no_kpj')->nullable();
            $table->enum('bpjs_ket', ['ya', 'tidak'])->default('tidak');
            $table->string('no_kis')->nullable();
            $table->string('sk_file');
            $table->string('contract_file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_jobs_informations');
    }
}
