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
        Schema::create('slike', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->string('putanja',100);
            $table->bigInteger('donacija_id')->unsigned();
            $table->foreign('donacija_id')->references('id')->on('donacije')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slike');
    }
};
