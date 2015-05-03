<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDokumentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dokuments', function(Blueprint $table)
		{
			$table->increments('id');
                       $table->string('dokuname')->unique();                      
			$table->string('author');
                        $table->string('group');
                        $table->string('aktive');                       
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dokuments');
	}

}
