<?php

class FolderTableSeeder extends Seeder {

	public function run() {
    $faker = \Faker\Factory::create();

    for ($i=0; $i < 10; $i++) { 
      $name = $faker->words(rand(1, 3));

      $name = implode($name, ' ');

      $folder = \Folder::create([
        'name' => ucfirst($name),
        'slug' => \Str::slug($name)
      ]);

      // Arrach the folder to a random
      \User::orderBy(DB::raw('RAND()'))->first()->folders()->save($folder);
    }
  }
}
