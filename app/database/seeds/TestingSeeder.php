<?php

/**
 * This seeder is only supposed to be run agains the in memory sqlite db for testing
 */
class TestingSeeder extends Seeder {

	public function run()
	{
    Eloquent::unguard();

		$user = User::create(['email' => 'boyan@devsave.dev', 'password' => Hash::make('1234')]);

    Folder::create(['name' => 'First folder', 'slug' => 'first-folder', 'user_id' => $user->id]);
    Folder::create(['name' => 'Second folder', 'slug' => 'second-folder', 'user_id' => $user->id]);
    Folder::create(['name' => 'Third folder', 'slug' => 'third-folder', 'user_id' => $user->id]);
    Folder::create(['name' => 'Fourh folder', 'slug' => 'fourh-folder', 'user_id' => $user->id]);

    Tag::create(['name' => 'First tag', 'slug' => 'first-tag', 'user_id' => $user->id]);
    Tag::create(['name' => 'Second tag', 'slug' => 'second-tag', 'user_id' => $user->id]);
    Tag::create(['name' => 'Third tag', 'slug' => 'third-tag', 'user_id' => $user->id]);
    Tag::create(['name' => 'Fourh tag', 'slug' => 'fourh-tag', 'user_id' => $user->id]);

    $bookmarksData = [
      ['title' => 'DevSave',    'user_id' => 1, 'url' => 'http://devsave.dev',                'notes' => 'Notes on the bookmark'],
      ['title' => 'Example',    'user_id' => 1, 'url' => 'http://example.dev',                'notes' => 'Notes on the bookmark', 'folder_id' => 1],
      ['title' => 'Some org',   'user_id' => 1, 'url' => 'http://some-org.dev',               'notes' => 'Notes on the bookmark', 'folder_id' => 1],
      ['title' => 'Some biz',   'user_id' => 1, 'url' => 'http://some-biz.dev',               'notes' => 'Notes on the bookmark', 'folder_id' => 1],
      ['title' => 'Laravel',    'user_id' => 1, 'url' => 'http://laravel.com',                'notes' => 'Notes on the bookmark', 'folder_id' => 3],
      ['title' => 'AngularJS',  'user_id' => 1, 'url' => 'http://angularjs.org',              'notes' => 'Notes on the bookmark', 'folder_id' => 3],
      ['title' => 'PHPUnit',    'user_id' => 1, 'url' => 'http://phpunit.de',                 'notes' => 'Notes on the bookmark'],
      ['title' => 'Jasmine',    'user_id' => 1, 'url' => 'http://pivotal.github.io/jasmine/', 'notes' => 'Notes on the bookmark'],
      ['title' => 'GitHub',     'user_id' => 1, 'url' => 'http://github.com',                 'notes' => 'Notes on the bookmark', 'folder_id' => 4],
    ];

    foreach ($bookmarksData as $bookmark) {
      Bookmark::create($bookmark);
    }

    // Attach tags to the bookmarks
     
    Bookmark::find(1)->tags()->attach(1);
    Bookmark::find(1)->tags()->attach(2);

    Bookmark::find(2)->tags()->attach(1);
    Bookmark::find(2)->tags()->attach(3);

    Bookmark::find(3)->tags()->attach(1);
    Bookmark::find(3)->tags()->attach(2);
    Bookmark::find(3)->tags()->attach(3);

    Bookmark::find(4)->tags()->attach(1);

    Bookmark::find(5)->tags()->attach(2);
    Bookmark::find(5)->tags()->attach(3);

    Bookmark::find(6)->tags()->attach(1);
    Bookmark::find(6)->tags()->attach(2);

    Bookmark::find(7)->tags()->attach(1);
    Bookmark::find(7)->tags()->attach(3);

    Bookmark::find(8)->tags()->attach(2);

    // Tag with id 1 one has 6 bookmarks
    // Tag with id 2 one has 5 bookmarks
    // Tag with id 3 one has 4 bookmarks
    // Tag with id 4 one has 0 bookmarks
    // Bookmarks with id 9 has 0 tags
  
	}

}
