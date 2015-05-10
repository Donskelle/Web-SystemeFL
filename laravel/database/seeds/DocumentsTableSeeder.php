<?php

use App\Models\document;
use Illuminate\Database\Seeder;

class DocumentsTableSeeder extends Seeder {

    public function run() {
        \DB::table('documents')->truncate();
        $documents = [
            [
                'name' => "DokuMummy",
                'path' => "DokuMummy",
                'user_id' => 1
            ],
            [
               'name' => "Nagios jb",
                'path' => "Nagios jb",
                'user_id' => 2
            ],
            [
              'name' => "test",
                'path' => "test",
                'user_id' => 2
            ],
            [
               'name' => "test",
                'path' => "test",
                'user_id' => 2
            ]
        ];
        foreach ($documents as $document) {
            document::create($document);
        }
    }

}
