<?php

namespace Database\Seeders;

use App\Models\Company\updateProjectDataModel;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class UpdateProjectDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // set_time_limit(0);
        // $projects = (new updateProjectDataModel())->getProjects();
        // foreach ($projects as $project){
        //     $data = (new updateProjectDataModel())->getProjectEditData($project);
        //     //$result = (new updateProjectDataModel())->ProjectEditSave($data, $project);

        //     if($data)
        //         dump('Project Id: '.$project->id. ' , Title: '. $project->title.' = edited.');
        // }

    }
}
