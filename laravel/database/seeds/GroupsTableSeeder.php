<?php

use App\Models\group;
use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder {

    public function run() {
        \DB::table('groups')->truncate();
        $groups = [
            [
                'name' => "Entwicklung",
                'description' => "Beinhaltet verschiedene Programmiersprachen",
                'active' => 1
            ],
            [
                'name' => "Verwaltung",
                'description' => "Abrechnungen Stundenzettel",
                'active' => 1
            ],
            [
                'name' => "Marketing",
                'description' => "Beinhaltet verschiedene Flyer",
                'active' => 1
            ],
            [
                'name' => "Verkauf",
                'description' => "Abschlüsse",
                'active' => 1
            ]
        ];
        foreach ($groups as $group) {
            group::create($group);
        }
    }

}
