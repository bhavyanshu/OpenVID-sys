<?php

use Illuminate\Database\Seeder;

class NotificationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('notification_categories')->delete();
      DB::table('notification_categories')->insert(
      array(
        array (
          'id' => '1',
          'name' => 'user.postedflaw',
          'text' => 'posted a security flaw for your product'
          ),
        array (
          'id' => '2',
          'name' => 'user.postedcomment',
          'text' => 'posted a comment on vulnerability report'
          ),
        array (
          'id' => '3',
          'name' => 'vendor.markedfixed',
          'text' => 'vendor updated report status!'
          )
        ));
      }
}
