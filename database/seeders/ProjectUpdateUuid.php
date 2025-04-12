<?php

namespace Database\Seeders;

use App\Models\Company\updateProjectDataModel;
use Illuminate\Database\Seeder;

class ProjectUpdateUuid extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        set_time_limit(0);
        (new updateProjectDataModel())->projectUuidUpdate();

    }
}
