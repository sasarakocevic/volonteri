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
        Schema::create('volonteri', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->string('email',100);
            $table->string('ime');
            $table->bigInteger('akcija_id')->unsigned();
            $table->foreign('akcija_id')->references('id')->on('akcije')->onDelete('cascade');
            $table->unique(['email', 'akcija_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('volonteri');
    }
};
