<?php

use Illuminate\Database\Seeder;

class SUSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
   public function run()
  	{
  		DB::table('users')->insert(
  			array(
  				array (
  					'role_id' => '1',
  					'username' => 'superadmin',
  					'email' => 'superadmin@org.com',
  					'password' => Hash::make('superadmin'), //default password - MUST BE CHANGED VIA in-APP security settings!
  					'confirmation_code' => md5(uniqid(mt_rand(), true)),
  					'confirmed' => true,
  					'blocked' => false,
  					'created_at' => date('Y-m-d H:i:s'),
  					'updated_at' => date('Y-m-d H:i:s')
  					),
  				));
  		DB::table('orgprofiles')->insert(
  			array(
  				array (
  					'user_org_id' => '1',
  					'created_at' => date('Y-m-d H:i:s'),
  					'updated_at' => date('Y-m-d H:i:s')
  					),
  				));
  	  }
}
