<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class Groupmessages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [148, 160];
        for($i=0; $i<10; $i++)
            DB::table('group_messages')->insert([
                'group_id' => 2,
                'from_user' => $users[array_rand($users, 1)],
                'parent_msg' => 0,
                'thread_count' => 0,
                'content' => json_encode(['content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s', 'type' => 'text']),
                'created_at' => date('Y-m-d H:i:s')
            ]);
    }
}
