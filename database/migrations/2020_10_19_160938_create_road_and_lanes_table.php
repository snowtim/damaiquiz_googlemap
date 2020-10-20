<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoadAndLanesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('road_and_lanes', function (Blueprint $table) {
            $table->id();
            $table->integer('zip_id');            //郵遞區號id
            $table->string('filename_id');        //Adress 檔案id
            $table->string('name', 32);           //Data of road, lane and alley
            $table->string('abc', 32);            //Mandarin Phonetic Symbols
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
        Schema::dropIfExists('road_and_lanes');
    }
}
