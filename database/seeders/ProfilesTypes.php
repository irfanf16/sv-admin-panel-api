<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class ProfilesTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profile_types = array(
            array('id' => '1','title' => 'Admin','description' => '','created_at' => '2021-11-24 11:45:17','updated_at' => '2021-11-24 11:45:17'),
            array('id' => '2','title' => 'Manager','description' => '','created_at' => '2021-11-24 11:46:20','updated_at' => '2021-11-24 11:46:20'),
            array('id' => '3','title' => 'Viewer','description' => 'View activities only','created_at' => '2021-11-24 11:47:02','updated_at' => '2021-11-24 11:47:02'),
            array('id' => '4','title' => 'User','description' => NULL,'created_at' => '2021-11-24 11:47:59','updated_at' => '2021-11-24 11:47:59')
        );
        DB::table('profile_types')->insert($profile_types);
    }
}
