<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSansTable extends Migration
{
    public function up()
    {
        Schema::create('sans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idquan');
            $table->string('name');
            $table->integer('numberpeople');
            $table->boolean('tranhthai');
            $table->bigInteger('priceperhour');
            
            
        });
    }
    public function down()
    {
        Schema::dropIfExists('sans');
    }
}
