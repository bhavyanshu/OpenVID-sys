<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Orgprofiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
       Schema::create('orgprofiles', function(Blueprint $table)
       {
         $table->increments('org_id');
         $table->integer('user_org_id')->unsigned();
         $table->foreign('user_org_id')->references('id')->on('users')->onDelete('cascade');
         $table->string('legal_name')->nullable();
         $table->string('display_name')->nullable();
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
       Schema::drop('orgprofiles');
     }
}
