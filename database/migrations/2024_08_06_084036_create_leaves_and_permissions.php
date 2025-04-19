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
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('user_id')->constrained('users');
            $table->text('reason');
            $table->enum('leaves_status', ['Sakit', 'Cuti', 'Izin']);
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
