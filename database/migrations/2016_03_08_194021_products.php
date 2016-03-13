<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('products', function(Blueprint $table)
      {
        $table->increments('p_id');
        $table->integer('user_p_id')->unsigned();
        $table->foreign('user_p_id')->references('id')->on('users')->onDelete('cascade');
        $table->string('p_name')->nullable();
        $table->string('p_author_name')->nullable();
        $table->string('p_author_email')->nullable();
        $table->string('p_description')->nullable();
        $table->string('p_url')->nullable();
        $table->string('p_type')->nullable();
        $table->string('p_status')->default(true);
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
      Schema::drop('products');
    }
}
