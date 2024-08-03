<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlacementIdToUserJobsInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void

    {
        Schema::table('user_jobs_informations', static function (Blueprint $table) {
            $table->foreignId('placement_id')->constrained('user_placements')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('user_jobs_informations', function (Blueprint $table) {
            //
        });
    }
}
