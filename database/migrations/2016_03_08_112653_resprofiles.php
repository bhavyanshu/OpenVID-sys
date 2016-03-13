<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Resprofiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('resprofiles', function(Blueprint $table)
      {
        $table->increments('res_id');
        $table->integer('user_res_id')->unsigned();
        $table->foreign('user_res_id')->references('id')->on('users')->onDelete('cascade');
        $table->string('first_name')->nullable();
        $table->string('last_name')->nullable();
        $table->string('profpic')->nullable();
        $table->string('mobilenumber')->nullable();
        $table->string('address')->nullable();
        $table->string('pincode')->nullable();
        $table->string('gender')->nullable();
        $table->string('bio')->nullable();
        $table->string('designation')->nullable();
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
      Schema::drop('resprofiles');
    }
}
