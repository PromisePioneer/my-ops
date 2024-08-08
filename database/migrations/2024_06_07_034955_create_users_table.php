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
            $table->string('absent_id', 4)->unique();
            $table->enum('placement', ['Pusat', 'Cabang']);
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->string('nip')->unique();
            $table->string('name');
            $table->date('join_date');
            $table->string('email')->unique();
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
