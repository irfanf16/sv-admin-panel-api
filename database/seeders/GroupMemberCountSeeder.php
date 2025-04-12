<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class GroupMemberCountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group_data = DB::table('groups')->get(['id']);
        foreach($group_data as $key => $group_id){
            // dump($group_id->id);
            $members = DB::table('groups')
            ->join('group_members', 'group_members.group_id', '=', 'groups.id')
            ->select('group_members.group_id', DB::raw("count(group_members.user_id) as members_count"))
            ->groupBy('group_members.group_id')
            ->where('group_members.group_id',$group_id->id)
            ->get();
            if(isset($members[0])){
                // dump('group_id : '.$members[0]->group_id,'members_count : '.$members[0]->members_count);
                DB::table('groups')
                    ->where('id',$members[0]->group_id)
                    ->update([ 
                        'members_count' => $members[0]->members_count
                    ]);
            }
        }
    }
}
