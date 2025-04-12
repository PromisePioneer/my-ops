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
        Schema::table('sp', function (Blueprint $table) {
            $table->foreignId('punished_by_role_id')
                ->after('punished_by')
                ->nullable()
                ->constrained('roles')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('known_by_user_id')
                ->after('punished_by_role_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('known_by_role_id')
                ->after('known_by_user_id')
                ->constrained('roles')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sp', function (Blueprint $table) {
            //
        });
    }
};
