<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFab extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fab', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('contact_id');
            $table->string('fab_number');
            $table->enum('subscription_status', ['baru', 'perubahan jenis layanan', 'daftar ulang']);
            $table->date('date');
            $table->text('billing_address');
            $table->text('installation_address')->nullable();
            $table->string('zip_code');
            $table->string('file');
            $table->boolean('status_confirmation')->default(false);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('contact_id')
                ->references('id')
                ->on('contacts')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fab');
    }
}
