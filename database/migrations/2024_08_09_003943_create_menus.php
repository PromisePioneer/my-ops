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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->string('nama_menu')->nullable();
            $table->string('link_menu')->nullable();
            $table->string('deskripsi_menu')->nullable();
            $table->string('icon_menu')->nullable();
            $table->string('level_menu')->nullable();
            $table->integer('no_urut')->nullable();
            $table->string('class_active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
