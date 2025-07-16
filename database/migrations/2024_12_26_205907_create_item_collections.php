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
        Schema::create('item_collections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['ASET', 'JUAL']);
            $table->string('code')->nullable();
            $table->enum('tangible_assets_type', ['Tanah','Bangunan', 'Bukan Bangunan'])->nullable();
            $table->boolean('is_vehicle')->default(false);
            $table->enum('non_building_group',
                ['Kelompok I', 'Kelompok II', 'Kelompok III', 'Kelompok IV'])
                ->nullable();
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('item_categories')
                ->cascadeOnDelete();
            $table->foreignId('unit_type_id')
                ->constrained('unit_types')
                ->cascadeOnDelete();
            $table->foreignId('asset_account_id')
                ->nullable()
                ->constrained('accounts')
                ->cascadeOnDelete();
            $table->enum('building_type', ['Permanen', 'Tidak Permanen'])->nullable();
            $table->double('reorder_level')->nullable();
            $table->boolean('must_have_code')->default(false);
            $table->boolean('is_code_listed')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('item_collections');
    }
};
