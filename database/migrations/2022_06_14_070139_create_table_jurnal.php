<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_jurnal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_piutang');
            $table->string('no_jurnal');
            $table->string('keterangan');
            $table->string('kode_perkiraan');
            $table->string('nama_perkiraan');
            $table->string('flag');
            $table->bigInteger('nominal');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('id_piutang')->references('id')->on('piutang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_jurnal');
    }
};
