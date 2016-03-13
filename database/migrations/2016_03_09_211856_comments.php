<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('comments', function(Blueprint $table)
      {
        $table->increments('com_id');
        $table->integer('user_com_id')->unsigned();
        $table->foreign('user_com_id')->references('id')->on('users')->onDelete('cascade');
        $table->integer('com_vul_id')->unsigned();
        $table->foreign('com_vul_id')->references('vul_id')->on('vulnerabilities')->onDelete('cascade');
        $table->string('com_text')->nullable();
        $table->string('com_status')->default(true);//1->active, 2->awaiting approval, 3->disabled
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
      Schema::drop('comments');
    }
}
