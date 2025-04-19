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
            $table->enum('leaves_status', ['Sakit', 'Cuti', 'Izin', 'Cuti Penting'])->change();
            $table->enum('important_leaves', ['Menikah', 'Menikahkan Anak', 'Mengkhitankan Anak', 'Membaptis Anak', 'Istri Melahirkan', 'Anggota Keluarga Meninggal Dunia', 'Anggota Keluarga Dalam Satu Rumah Meninggal Dunia'])->after('leaves_status')->nullable();
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
