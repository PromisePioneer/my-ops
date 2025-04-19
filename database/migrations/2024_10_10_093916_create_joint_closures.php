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
        Schema::create('joint_closures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('code_id')->constrained('joint_closures_code');
            $table->string('region');
            $table->foreignId('fo_cable_id')->constrained('fo_cables');
            $table->double('lat');
            $table->double('long');
            $table->date('cut_off_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('joint_closures');
    }
};
