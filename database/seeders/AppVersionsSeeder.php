<?php

namespace Database\Seeders;

use App\Models\Company\AppVersion;
use Illuminate\Database\Seeder;

class AppVersionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $versions = array(
            array(
                'status'=> 1
            ),
        );
        AppVersion::insert($versions);
    }
}
