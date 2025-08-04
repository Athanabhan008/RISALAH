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
        Schema::create('prwapus', function (Blueprint $table) {
            $table->id();
            $table->integer('id_sales');
            $table->string('nama_projek');
            $table->string('nomor_pr');
            $table->string('insentip_sales');
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
        Schema::dropIfExists('prwapus');
    }
};
