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
        Schema::create('sp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('sp_number');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('sp_type', ['ST', 'SP-1', 'SP-2', 'SP-3']);
            $table->json('list_of_reason');
            $table->foreignId('punished_by')->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->boolean('expired_if_has_new_sp')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('sp');
    }
};
