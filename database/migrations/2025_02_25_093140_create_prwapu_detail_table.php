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
        Schema::create('prwapu_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('id_prwapu');
            $table->string('partnumber_description');
            $table->string('vendor');
            $table->string('unit_price');
            $table->string('total_price');
            $table->string('qty');
            $table->string('vendor_price');
            $table->string('unit_price_cv');
            $table->string('total_po_cv');
            $table->string('total_cost');
            $table->string('margin');
            $table->string('persentase');
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
        Schema::dropIfExists('prwapu_detail');
    }
};
