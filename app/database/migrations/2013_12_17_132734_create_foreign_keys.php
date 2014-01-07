<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('bookmarks', function(Blueprint $table) {
			$table->engine = 'InnoDB';

			$table->foreign('folder_id')->references('id')->on('folders');
			$table->foreign('user_id')->references('id')->on('users');
		});

		Schema::table('folders', function(Blueprint $table) {
			$table->engine = 'InnoDB';

			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});

		Schema::table('tags', function(Blueprint $table) {
			$table->engine = 'InnoDB';

			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});

		Schema::table('bookmark_tag', function(Blueprint $table) {
			$table->engine = 'InnoDB';

			$table->foreign('bookmark_id')->references('id')->on('bookmarks')
						->onDelete('cascade')
						->onUpdate('cascade');
		});

		Schema::table('bookmark_tag', function(Blueprint $table) {
			$table->engine = 'InnoDB';

			$table->foreign('tag_id')->references('id')->on('tags')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
	}

	public function down()
	{
		Schema::table('bookmarks', function(Blueprint $table) {
			$table->dropForeign('bookmarks_folder_id_foreign');
		});
		Schema::table('folders', function(Blueprint $table) {
			$table->dropForeign('folders_user_id_foreign');
		});
		Schema::table('tags', function(Blueprint $table) {
			$table->dropForeign('tags_user_id_foreign');
		});
		Schema::table('bookmark_tag', function(Blueprint $table) {
			$table->dropForeign('bookmark_tag_bookmark_id_foreign');
		});
		Schema::table('bookmark_tag', function(Blueprint $table) {
			$table->dropForeign('bookmark_tag_tag_id_foreign');
		});
	}
}