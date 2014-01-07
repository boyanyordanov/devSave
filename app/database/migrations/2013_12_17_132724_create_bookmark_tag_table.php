<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBookmarkTagTable extends Migration {

	public function up()
	{
		Schema::create('bookmark_tag', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('bookmark_id')->unsigned();
			$table->integer('tag_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('bookmark_tag');
	}
}