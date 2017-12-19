<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DummiesTable extends Migration
{
    public function up()
    {
        Schema::create('dummies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('relation_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dummies');
    }
}
