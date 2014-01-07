<?php

class BookmarksTableSeeder extends Seeder {

	public function run() {
    $faker = \Faker\Factory::create();

    for ($i=0; $i < 30; $i++) { 
      $bookmark = Bookmark::create([
        'url' => $faker->url,
        'title' => $faker->domainName,
        'notes' => $faker->paragraph()
      ]);

      // Attach the bookmark to a random user
      $user = \User::orderBy(DB::raw('RAND()'))->first();

      $user->bookmarks()->save($bookmark);

      // Insert each even id in a folder
      if ($i%2 == 0 && $user->folders()->count() > 0) {
        $user->folders()->orderBy(DB::raw('RAND()'))->first()->bookmarks()->save($bookmark);
      }

      if ($user->tags()->count() > 0) {
        $tags = $user->tags()->orderBy(DB::raw('RAND()'))->take(rand(0, $user->tags()->count()))->get();

        foreach ($tags as $tag) {
          $tag->bookmarks()->attach($bookmark);
        }
      }
    }
	}
}
