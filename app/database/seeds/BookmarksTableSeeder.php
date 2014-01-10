<?php

class BookmarksTableSeeder extends Seeder {

	public function run() {
    $faker = \Faker\Factory::create();

    // Attach the bookmark to a random user
    if (Config::get('database.default') == 'mysql') {
      $user = \User::orderBy(DB::raw('RAND()'))->first();
    } else if (Config::get('database.default') == 'sqlite') {
      $user = \User::orderBy(DB::raw('RANDOM()'))->first();
    }

    for ($i=0; $i < 30; $i++) { 
      $bookmark = Bookmark::create([
        'url'     => $faker->url,
        'title'   => $faker->domainName,
        'notes'   => $faker->paragraph(),
        'user_id' => $user->id
      ]);

      // Insert each even id in a folder
      if ($i%2 == 0 && $user->folders()->count() > 0) {
        if (Config::get('database.default') == 'mysql') {
          $user->folders()->orderBy(DB::raw('RAND()'))->first()->bookmarks()->save($bookmark);
        } else if (Config::get('database.default') == 'sqlite') {
          $user->folders()->orderBy(DB::raw('RANDOM()'))->first()->bookmarks()->save($bookmark);
        }
      }

      if ($user->tags()->count() > 0) {
        if (Config::get('database.default') == 'mysql') {
          $tags = $user->tags()->orderBy(DB::raw('RAND()'))->take(rand(0, $user->tags()->count()))->get();
        } else if (Config::get('database.default') == 'sqlite') {
          $tags = $user->tags()->orderBy(DB::raw('RANDOM()'))->take(rand(0, $user->tags()->count()))->get();
        }

        foreach ($tags as $tag) {
          $tag->bookmarks()->attach($bookmark);
        }
      }
    }
	}
}
