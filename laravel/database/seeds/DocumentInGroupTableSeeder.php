<?php

use App\Models\document_in_group;
use Illuminate\Database\Seeder;

class DocumentInGroupTableSeeder extends Seeder {

    public function run() {
        \DB::table('document_in_group')->truncate();
        $documents_in_groups = [
            [
                'document_id' => "1",
                'group_id' => "1"
            ],
            [
                'document_id' => "2",
                'group_id' => "1"
            ],
            [
                'document_id' => "2",
                'group_id' => "3"
            ]
        ];
        foreach ($documents_in_groups as $document_in_group) {
            document_in_group::create($document_in_group);
        }
    }

}
