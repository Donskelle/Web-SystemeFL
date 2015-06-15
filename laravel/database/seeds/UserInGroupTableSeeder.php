<?php

use App\Models\user_in_group;
use Illuminate\Database\Seeder;

class UserInGroupTableSeeder extends Seeder {

    public function run() {
        \DB::table('user_in_group')->truncate();
        $users_in_groups = [
            [
                'user_id' => "1",
                'group_id' => "1"
            ],
            [
                'user_id' => "2",
                'group_id' => "1"
            ],
            [
                'user_id' => "2",
                'group_id' => "3"
            ]
        ];
        foreach ($users_in_groups as $user_in_group) {
            user_in_group::create($user_in_group);
        }
    }

}
