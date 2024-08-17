<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', static function (Blueprint $table) {
            $table->id();
            $table->string('absent_id');
            $table->integer('pri')->default(0);
            $table->enum('placement', ['Pusat', 'Cabang'])->default('Pusat');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('card')->nullable();
            $table->integer('grp')->default(1);
            $table->string('tz')->default('0000000100000000');
            $table->integer('verify')->default(0);
            $table->string('vice_card')->nullable();
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('end_datetime')->nullable();
            $table->string('nip')->unique();
            $table->string('name')->nullable();
            $table->date('join_date')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->dateTime('last_login')->nullable();
            $table->string('profile_pic')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
