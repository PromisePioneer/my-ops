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
        Schema::create('att_manual_has_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('att_manual_request_id')->constrained('att_manual_request')->cascadeOnDelete();
            $table->string('attachment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('att_manual_has_attachments');
    }
};
