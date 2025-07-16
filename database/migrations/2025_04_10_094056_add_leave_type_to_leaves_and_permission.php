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
        Schema::table('leaves_and_permissions', function (Blueprint $table) {
            $table->enum('leaves_status', ['Sakit', 'Cuti', 'Izin', 'Cuti Penting', 'Lembur'])->change();
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
            ])->after('leaves_status')->nullable();
            $table->text('reason')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_and_permission', function (Blueprint $table) {
            //
        });
    }
};
