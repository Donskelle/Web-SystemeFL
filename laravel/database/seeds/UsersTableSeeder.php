<?php

use App\Models\user;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

    public function run() {
        \DB::table('users')->truncate();
        $users = [
            [
                'username' => "admin",
                'name' => "System Admin",
                'extra' => "Admin",
                'password' => bcrypt("admin"),
                'active' => 1,
                'permission' => 0, // 0 = Admin , 1 = UserAdmin, 2 = User
                'imagePath' => "default0.png"
            ],
            [
                'username' => "useradmin",
                'name' => "User Admin",
                'extra' => "UserAdmin",
                'password' => bcrypt("admin"),
                'active' => 1,
                'permission' => 1, // 0 = Admin , 1 = UserAdmin, 2 = User
                'imagePath' => "default1.png"
            ],
            [
                'username' => "user",
                'name' => "User",
                'extra' => "User",
                'password' => bcrypt("user"),
                'active' => 1,
                'permission' => 2, // 0 = Admin , 1 = UserAdmin, 2 = User
                'imagePath' => "default2.png"
            ]
        ];
        foreach ($users as $user) {
            user::create($user);
        }
    }

}
