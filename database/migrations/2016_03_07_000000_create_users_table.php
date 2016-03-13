<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('role_id')->unsigned();
          $table->foreign('role_id')->references('rid')->on('roles')->onDelete('cascade');
          $table->string('username')->unique();
          $table->string('email')->unique();
          $table->string('password', 60);
          $table->string('confirmation_code');
          $table->boolean('confirmed')->default(false);
          $table->boolean('blocked')->default(true);
          $table->rememberToken();
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
        Schema::drop('users');
    }
}
