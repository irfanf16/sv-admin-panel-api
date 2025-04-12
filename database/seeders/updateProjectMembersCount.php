<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class updateProjectMembersCount extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = \DB::table('projects')->where(['is_deleted' => 0])->get();

        if(count($projects) > 0){
            foreach ($projects as $project)
            {
                $p_u_count = \DB::table('user_projects')->selectRaw('COUNT(DISTINCT user_projects.user_id) as users')->where('project_id','=',$project->id)->first();
                \DB::select(' update projects set members_count = '.$p_u_count->users.' where projects.id = '.$project->id.' ');
            }

        }
    }
}
