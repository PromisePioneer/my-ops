<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leaves_and_permissions', static function (Blueprint $table) {
            $table->id();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('leaves_status', ['Sakit', 'Cuti', 'Izin', 'Cuti Penting', 'Lembur']);
            $table->enum('important_leaves', [
                'Menikah', // 3 days
                'Menikahkan Anak', // 3 days
                'Istri Melahirkan', // 3 days
                'Anggota Keluarga Meninggal Dunia', // 3 days
                'Membaptis Anak', // 2hari
                'Mengkhitankan Anak', // 2 hari
                'Anggota Keluarga Dalam Satu Rumah Meninggal Dunia', // 1 hari
                'Pemakaman Saudara Kandung', // 1 hari
                'Memenuhi Panggilan Instansi Pemerintah', // ditetapkan perusahaan
                'Mendapat Musibah', // ditetapkan perusahaan
            ]);
            $table->text('reason')->nullable();
            $table->enum('confirmation_status', ['Diproses', 'Diterima', 'Ditolak'])->default('Diproses');
            $table->string('sick_letter')->nullable();
            $table->string('confirmation_reason')->nullable();
            $table->foreignId('acc_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('leaves_and_permissions');
    }
};
