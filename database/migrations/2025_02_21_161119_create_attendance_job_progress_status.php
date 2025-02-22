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
        Schema::create('attendance_job_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('fp_devices');
            $table->enum('status', ['Pending', 'Sukses', 'Gagal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_job_progress_status');
    }
};
