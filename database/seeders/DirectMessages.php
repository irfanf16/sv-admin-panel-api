<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DB;

class DirectMessages extends Seeder
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
            DB::table('direct_messages')->insert([
                'from_user' => $users[array_rand($users, 1)],
                'to_user' => $users[array_rand($users, 1)],
                'parent_msg' => 0,
                'thread_count' => 0,
                'content' => json_encode(['content' => Str::random(200), 'type' => 'text']),
                'created_at' => date('Y-m-d H:i:s')
            ]);
    }
}
