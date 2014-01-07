<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFoldersTable extends Migration {

	public function up()
	{
		Schema::create('folders', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->string('slug');
			$table->integer('user_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('folders');
	}
}