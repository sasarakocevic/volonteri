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
        Schema::create('akcije', function (Blueprint $table) {
            $table->id();
            $table->string('naslov',200);
            $table->text('opis');
            $table->dateTime('vrijeme');
            $table->integer('pozeljan_broj_volontera');
            $table->string('status',20);
            $table->text('izvjestaj')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('akcije');
    }
};
