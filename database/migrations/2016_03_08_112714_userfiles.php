<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Userfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('userfiles', function(Blueprint $table)
      {
        $table->increments('file_id');
        $table->integer('file_user_id')->unsigned();
        $table->foreign('file_user_id')->references('id')->on('users')->onDelete('cascade');
        $table->string('file_name')->nullable();
        $table->string('file_description')->nullable();
        $table->string('file_token')->nullable();
        $table->boolean('can_user_access')->default(true);
        $table->boolean('can_user_delete')->default(true);
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
      Schema::drop('userfiles');
    }
}
