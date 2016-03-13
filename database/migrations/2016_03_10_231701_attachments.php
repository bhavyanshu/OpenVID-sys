<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Attachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('attachments', function(Blueprint $table)
      {
        $table->increments('at_id');
        $table->integer('user_at_id')->unsigned();
        $table->foreign('user_at_id')->references('id')->on('users')->onDelete('cascade');
        $table->integer('at_com_id')->unsigned();
        $table->foreign('at_com_id')->references('com_id')->on('comments')->onDelete('cascade');
        $table->string('file_name')->nullable();
        $table->string('file_token')->nullable();
        $table->string('at_statu')->default(true);//1->visible, 2->invisible
        $table->timestamps('created_at');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::drop('attachments');
    }
}
