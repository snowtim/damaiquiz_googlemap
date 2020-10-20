<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->integer('zip');               //郵遞區號
            $table->string('city', 32);           //城巿
            $table->string('area', 32);           //區
            $table->string('road', 32);           //路
            $table->integer('lane');              //巷
            $table->integer('alley');             //弄
            $table->integer('no');                //號
            $table->integer('floor');             //樓
            $table->string('address', 255);       //其他資訊
            $table->string('filename', 8);        //Adress 檔案
            $table->float('latitude');
            $table->float('lontitue');
            $table->string('full_address', 255);  //整理過的完整地址 
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
        Schema::dropIfExists('addresses');
    }
}
