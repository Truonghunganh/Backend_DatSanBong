<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoanhThusTable extends Migration
{
    public function up()
    {
        Schema::create('doanhthus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idquan');
            $table->bigInteger('doanhthu');
            $table->dateTime('time');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('doanhthus');
    }
}
