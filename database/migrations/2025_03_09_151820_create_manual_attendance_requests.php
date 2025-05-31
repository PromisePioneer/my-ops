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
        Schema::create('attendance_corrections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->date('date');
            $table->text('reason');
            $table->text('confirmation_reason')->nullable();
            $table->enum('attended_type', ['Checkin', 'Checkout']);
            $table->string('attachment');
            $table->enum('status', ['Awaiting Approval', 'Approved', 'Rejected'])->default('Awaiting Approval');
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_manual_request');
    }
};
