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
        Schema::create('fp_device_commands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('fp_devices');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->enum('type',
                [
                    'Pendaftaran Karyawan Baru',
                    'Tarik Data Absen Dari Mesin',
                    'Tarik Data Absen Dari Mesin Per Karyawan',
                ]);
            $table->text('command');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fp_device_command');
    }
};
