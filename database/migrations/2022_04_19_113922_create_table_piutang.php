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
        Schema::create('piutang', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice', 25);
            $table->date('tgl_pengajuan');
            $table->date('tgl_tempo');
            $table->unsignedBigInteger('id_debitur');
            $table->bigInteger('total_piutang');
            $table->string('status_piutang', 25);
            $table->boolean('isLocked')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('id_debitur')->references('id')->on('debitur')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('piutang');
    }
};
