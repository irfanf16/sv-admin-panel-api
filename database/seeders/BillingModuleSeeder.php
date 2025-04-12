<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BillingModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $database_name = DB::connection()->getDatabaseName();
        $modules = (new ModuleSeeder())->getModules();
        foreach ($modules as $key => $module) {
            $db_module = DB::table('modules')->where('id', $module['id'])->where('status', 1)->first();
            if(empty($db_module)) {
                if($module['url'] == 'module-billing/billing') {
                    $module['status'] = 1;
                }
                DB::table('modules')->insert($module);
                $add_perm = [
                                [
                                    'module_id' => $module['id'],
                                    'permission_id' => 1,
                                    'profile_id'  =>  1,
                                    'status'  => 0,
                                    'permanent_disabled' => 0,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                ],
                                [
                                    'module_id' => $module['id'],
                                    'permission_id' => 2,
                                    'profile_id'  =>  1,
                                    'status'  => 0,
                                    'permanent_disabled' => 0,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                ],
                                [
                                    'module_id' => $module['id'],
                                    'permission_id' => 3,
                                    'profile_id'  =>  1,
                                    'status'  => 0,
                                    'permanent_disabled' => 0,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                ],
                                [
                                    'module_id' => $module['id'],
                                    'permission_id' => 4,
                                    'profile_id'  =>  1,
                                    'status'  => 0,
                                    'permanent_disabled' => 0,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                ],
                            ];
                DB::table('profile_modules')->insert($add_perm);
            }
        }
    }
    
}
