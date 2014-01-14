<?php

class UsersRepositoryTest extends TestCase {

  protected $usersRepo;

  public function setUp () {
    parent::setUp();

    // Migrate the database
    Artisan::call('migrate');

    // Create some records to work with
    
    Artisan::call('db:seed', ['--class' => 'TestingSeeder']);

    // Get an instance of the repository
    $this->usersRepo = App::make('Devsave\Users\UsersInterface');
  }

  public function test_retrieving_users () {
    $results = $this->usersRepo->findAll();

    $this->assertCount(1, $results);    

    foreach ($results as $user) {
      $this->assertArrayHasKey('id', $user);
      $this->assertArrayHasKey('email', $user);
    }
  }

  public function test_retrieval_by_id () {
    $user = $this->usersRepo->findById(1);

    $this->assertArrayHasKey('id', $user);
    $this->assertArrayHasKey('email', $user);
  }

  public function test_retrieval_by_email () {
    $user = $this->usersRepo->findByEmail('boyan@devsave.dev');

    $this->assertArrayHasKey('id', $user);
    $this->assertArrayHasKey('email', $user);
  }

  public function test_creating_user () {
    $newUser = $this->usersRepo->create([
      'email'     => 'user@example.com',
      'password'  => '1234'
    ]);

    $this->assertArrayHasKey('id', $newUser);
    $this->assertArrayHasKey('email', $newUser);

    $this->assertEquals(2, $newUser['id']);
    $this->assertEquals('user@example.com', $newUser['email']); 
  }

  public function test_updating_user () {
    $updatedUser = $this->usersRepo->update([
      'id' => 1,
      'email' => 'updatedUser@example.com'
    ]);

    $this->assertArrayHasKey('id', $updatedUser);
    $this->assertArrayHasKey('email', $updatedUser);

    $this->assertEquals(1, $updatedUser['id']);
    $this->assertEquals('updatedUser@example.com', $updatedUser['email']); 
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_deleting_user () {
    $this->usersRepo->delete(1);

    $this->assertCount(0, $this->usersRepo->findAll());

    $this->usersRepo->findById(1);
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_querying_for_non_existent_user () {
    $this->usersRepo->findById(10);
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_updating_non_existent_user () {
    $this->usersRepo->update([
      'id' => 10,
      'email' => 'test@test.com'
    ]);
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_deleting_non_existend_user () {
    $this->usersRepo->delete(10);
  } 

}