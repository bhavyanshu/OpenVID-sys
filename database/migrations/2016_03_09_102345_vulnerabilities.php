<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Vulnerabilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('vulnerabilities', function(Blueprint $table)
      {
        $table->increments('vul_id');
        $table->integer('user_vul_author_id')->unsigned();
        $table->foreign('user_vul_author_id')->references('id')->on('users')->onDelete('cascade');
        $table->integer('vul_prod_id')->unsigned();
        $table->foreign('vul_prod_id')->references('p_id')->on('products')->onDelete('cascade');
        $table->string('vul_unique_id');
        $table->string('vul_author_name')->nullable();
        $table->string('vul_author_email')->nullable();

        /** vul_type
        *0 ->'Bypass authentication/restriction',
        *1 ->'Cross Site Scripting',
        *2 ->'Denial of service',
        *3 ->'Execute arbitrary code',
        *4 ->'Gain Privileges',
        *5 ->'Directory Traversal',
        *6 ->'Http Response Splitting',
        *7 ->'Memory Corruption',
        *8 ->'Overflow (stack/heap/other)',
        *9 ->'CSRF',
        *10 ->'File Inclusion',
        *11 ->'SQL Injection',
        *12 ->'Unrestricted Critical Information Access'
        */
        $table->string('vul_type')->nullable();
        $table->string('vul_complexity')->nullable(); //0->low, 1->medium, 2->high
        $table->string('vul_auth')->nullable(); //0->not required, 1->required
        $table->string('vul_confidentiality')->nullable(); //0->none, 1->partial, 2->complete
        $table->string('vul_integrity')->nullable(); //0->none, 1->partial, 2->complete
        $table->string('vul_performance')->nullable(); //0->none, 1->partial, 2->complete
        $table->string('vul_access')->nullable(); //0->none, 1->admin, 2->user, 3->other
        $table->string('vul_description',1000)->nullable();
        $table->string('ref_url_1')->nullable();
        $table->string('ref_url_2')->nullable();
        $table->string('ref_url_3')->nullable();
        $table->decimal('threat_level')->nullable();
        $table->string('patch_description',1000)->nullable();
        $table->string('patch_url')->nullable();
        $table->string('vul_status')->default(true); //0->fixed/closed , 1->open, 2->wontfix
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
      Schema::drop('vulnerabilities');
    }
}
