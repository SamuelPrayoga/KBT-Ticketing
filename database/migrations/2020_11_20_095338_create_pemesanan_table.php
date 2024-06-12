<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemesananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('kursi')->nullable();
            $table->datetime('waktu');
            $table->integer('total');
            $table->enum('status', ['Belum Bayar', 'Sudah Bayar'])->default('Belum Bayar');
            $table->unsignedBigInteger('rute_id');
            $table->unsignedBigInteger('penumpang_id');
            $table->unsignedBigInteger('petugas_id')->nullable();
            $table->timestamps();

            $table->foreign('rute_id')->references('id')->on('rute')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('penumpang_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('petugas_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemesanan');
    }
}
