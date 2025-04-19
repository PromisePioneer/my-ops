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
        Schema::create('fo_cables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->string('segment_id');
            $table->enum('classification', ['Backbone', 'Backhaul', 'Fronthaul', 'Akses']);
            $table->enum('cable_placement', ['Udara', 'Underground']);
            $table->string('cable_address');
            $table->integer('total_core');
            $table->double('starting_point_lat');
            $table->double('starting_point_long');
            $table->double('ending_point_lat');
            $table->double('ending_point_long');
            $table->double('length');
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
        Schema::dropIfExists('fo_cables');
    }
};
