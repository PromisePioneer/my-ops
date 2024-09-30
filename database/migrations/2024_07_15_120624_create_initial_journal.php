<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitialJournal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('initial_journal', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->foreignId('account_debit_id')->constrained('accounts')->onDelete('cascade');
            $table->foreignId('account_credit_id')->constrained('accounts')->onDelete('cascade');
            $table->double('initial_payment')->default(0);
            $table->boolean('status_confirmation')->default(0);
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
        Schema::dropIfExists('initial_journal');
    }
}
