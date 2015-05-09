<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Model::unguard();

        $this->call('UsersTableSeeder');
        $this->command->info('users Tabelle Gefüllt mit 3 Accounts');
        $this->call('GroupsTableSeeder');
        $this->command->info('groups Tabelle Gefüllt mit 4 Gruppen');
        $this->call('DocumentsTableSeeder');
        $this->command->info('documents Tabelle Gefüllt mit 4 Dokumenten');
        
        
    }

}
