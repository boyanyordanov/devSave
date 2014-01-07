<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBookmarksTable extends Migration {

	public function up()
	{
		Schema::create('bookmarks', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('url');
			$table->string('title', 255)->nullable();
			$table->string('notes', 500)->nullable();
			$table->integer('folder_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('bookmarks');
	}
}