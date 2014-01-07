<?php

class UsersTableSeeder extends Seeder {

	public function run()	{
		$faker = \Faker\Factory::create();

    // Create a default user for me 
    
    \User::create([
      'email' => 'netoholic@example.com',
      'password' => \Hash::make('1234')
    ]);

    for ($i=1; $i <= 5; $i++) { 
      $user = \User::create([
        'email' => $faker->email,
        'password' => \Hash::make('1234')
      ]);
    }
	}

}
