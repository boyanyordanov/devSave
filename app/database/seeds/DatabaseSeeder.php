<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Allow the tables to be trucated even with foreign key constraings
		if (Config::get('database.default') == 'mysql') {
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		}

		// Truncate the tables
		DB::table('bookmarks')->truncate();
		DB::table('folders')->truncate();
		DB::table('tags')->truncate();
		DB::table('users')->truncate();

		Eloquent::unguard();

		// $this->call('UserTableSeeder');
		$this->call('UsersTableSeeder');
		$this->call('FolderTableSeeder');
		$this->call('TagsTableSeeder');
		$this->call('BookmarksTableSeeder');

		// Enable the constraints again
		if (Config::get('database.default') == 'mysql') {
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		}
	}

}