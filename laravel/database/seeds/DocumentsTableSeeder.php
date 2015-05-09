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
                'autor_id' => 1
            ],
            [
               'name' => "Nagios jb",
                'path' => "Nagios jb",
                'autor_id' => 2
            ],
            [
              'name' => "test",
                'path' => "test",
                'autor_id' => 2
            ],
            [
               'name' => "test",
                'path' => "test",
                'autor_id' => 2
            ]
        ];
        foreach ($documents as $document) {
            document::create($document);
        }
    }

}
