<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatSansTable extends Migration
{
    public function up()
    {
        Schema::create('datsans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idsan');
            $table->bigInteger('iduser');
            $table->dateTime('start_time');
            $table->bigInteger('price');
            $table->boolean('xacnhan');
            $table->dateTime('Create_time');
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('datsans');
    }
}
