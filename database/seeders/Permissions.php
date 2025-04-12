<?php

namespace Database\Seeders;

use App\Libraries\Generic;
use Illuminate\Database\Seeder;
use DB;
class Permissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = array(
            array('id' => '1','type' => 'Add','description' => 'Can Add'),
            array('id' => '2','type' => 'Edit','description' => 'Can Edit'),
            array('id' => '3','type' => 'View','description' => 'Can View'),
            array('id' => '4','type' => 'Delete','description' => 'Can Delete')
        );
        $latest_company = session('temp_company');
        DB::connection($latest_company->company_initial)->table('permissions')->insert($permissions);
    }
}
