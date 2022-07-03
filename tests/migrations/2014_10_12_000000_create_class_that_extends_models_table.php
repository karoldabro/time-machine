<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassThatExtendsModelsTable extends Migration
{
    public function up()
    {
        Schema::create('class_that_extends_models', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('column_1')->nullable();
            $table->string('column_2')->nullable();
            $table->dateTime('column_3')->default('now');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('class_that_extends_models');
    }
};