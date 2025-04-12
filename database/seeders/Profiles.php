<?php
namespace Database\Seeders;

use App\Models\Company\Profile;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
class Profiles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profiles = array(
            array('id' => 1, 'title' => 'Owner','description' => NULL,'allow_tracking' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'profile_type' => '1'),
            array('id' => 2, 'title' => 'Manager','description' => 'Add task and time tracking','allow_tracking' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'profile_type' => '2'),
            array('id' => 3, 'title' => 'Viewer','description' => 'View activities only','allow_tracking' => '0','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'profile_type' => '3'),
            array('id' => 4, 'title' => 'User','description' => NULL,'allow_tracking' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'profile_type' => '4'),
            array('id' => 5, 'title' => 'Admin','description' => NULL,'allow_tracking' => '1','created_at' => Carbon::now(),'updated_at' => Carbon::now(),'profile_type' => '1')
        );
        Profile::insert($profiles);
    }
}