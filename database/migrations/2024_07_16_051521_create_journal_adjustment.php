<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalAdjustment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_adjustment', static function (Blueprint $table) {
            $table->id();
            $table->date('payment_date');
            $table->foreignId('initial_journal_id')->constrained('initial_journal')->onDelete('cascade');
            $table->string('description');
            $table->double('total_payment_per_month');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('journal_adjustment');
    }
}
