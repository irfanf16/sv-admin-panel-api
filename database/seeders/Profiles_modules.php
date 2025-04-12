<?php

namespace Database\Seeders;

use App\Models\Company\ProfileModules;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Libraries\Company_setup;

class Profiles_modules extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */ 
    public function run()
    {

        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('profile_modules')->truncate();
        
        $profile_modules = [];
        for ($profile_id=1; $profile_id<=5 ; $profile_id++) {  
            if($profile_id == 1 || $profile_id == 5) {
                $profile_type = 1;
            } else {
                $profile_type = $profile_id;
            }
            $profile_module = Company_setup::insert_perm($profile_id,$profile_type,true);
            $profile_modules = array_merge($profile_module, $profile_modules);
            
        }
        //setup ids for each row.
        $profile_id = 1;
        foreach($profile_modules as $key => $profile) {
            $profile_modules[$key]['id'] = $profile_id;
            $profile_id++;
        }
        DB::table('profile_modules')->insert($profile_modules);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
