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
        Schema::create('project_bonus', function (Blueprint $table) {
            $table->id();
            $table->date('date_active');
            $table->string('customer_name');
            $table->string('bast')->nullable();
            $table->string('baa')->nullable();
            $table->text('work_description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('project_bonus');
    }
};
