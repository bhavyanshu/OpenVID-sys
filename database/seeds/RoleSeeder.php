<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    DB::table('roles')->delete();
    DB::table('roles')->insert(
    array(
      array (
        'rid' => '1',
        'rolename' => 'Admin',
        'roleinfo' => 'Admin'
        ),
      array (
        'rid' => '2',
        'rolename' => 'Organization',
        'roleinfo' => 'Vendor/Organization info'
        ),
      array (
        'rid' => '3',
        'rolename' => 'Researcher',
        'roleinfo' => 'researcher info'
        )
      ));
    }
}
