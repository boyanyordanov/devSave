<?php

class TagsTableSeeder extends Seeder {

	public function run() {
    $faker = \Faker\Factory::create();

    // Arrach the tag to a random
    if (Config::get('database.default') == 'mysql') {
      $user = \User::orderBy(DB::raw('RAND()'))->first();
    } else if (Config::get('database.default') == 'sqlite') {
      $user = \User::orderBy(DB::raw('RANDOM()'))->first();
    }
    
    for ($i=0; $i < 10; $i++) { 
      $name = $faker->words(rand(1, 3));

      $name = implode($name, ' ');

      $tag = \Tag::create([
        'name'    => ucfirst($name),
        'slug'    => \Str::slug($name),
        'user_id' => $user->id
      ]);
    }
	}

}
