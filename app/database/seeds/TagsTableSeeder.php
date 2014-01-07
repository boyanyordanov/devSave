<?php

class TagsTableSeeder extends Seeder {

	public function run() {
    $faker = \Faker\Factory::create();

    for ($i=0; $i < 10; $i++) { 
      $name = $faker->words(rand(1, 3));

      $name = implode($name, ' ');

      $tag = \Tag::create([
        'name' => ucfirst($name),
        'slug' => \Str::slug($name)
      ]);

      // Arrach the tag to a random
      \User::orderBy(DB::raw('RAND()'))->first()->tags()->save($tag);
    }
	}

}
