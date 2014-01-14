<?php

use Mockery as m;

class UsersResourceControllerTest extends TestCase {

  protected $apiPrefix = '/api/v1';
	
  public function tearDown () {
    m::close();
  }

  public function test_getting_all_users () {
    $mock = $this->setupMock();

    $mock->shouldReceive('findAll')->once()->andReturn([
      ['id' => 1, 'email' => 'user@example.com'],
      ['id' => 2, 'email' => 'user2@example.com']
    ]);

    $response = $this->call('GET', $this->apiPrefix . '/users');

    $this->assertResponseOk();

    $this->assertJsonStringEqualsJsonString(
      '{"code": 200, "data": [{"id":1, "email": "user@example.com"}, {"id":2, "email": "user2@example.com"}]}',
      $response->getContent()
    );
  }

  public function test_getting_single_user () {
    $mock = $this->setupMock();
    
    $mock->shouldReceive('findById')->once()->with(1)->andReturn([
      'id'    => 1,
      'email' => 'user@example.com'
    ]);

    $response = $this->call('GET', $this->apiPrefix . '/users/1');

    $this->assertResponseOk();

    $this->assertJsonStringEqualsJsonString(
      '{"code": 200, "data": {"id":1, "email": "user@example.com"}}',
      $response->getContent()
    );
  }

  public function test_saving_user () {
    $mock = $this->setupMock();
    
    $mock->shouldReceive('create')->once()->with(['email' => 'newUser@example.com', 'password' => '1234'])->andReturn([
      'id' => 1,
      'email' => 'user@example.com'
    ]);

    $response = $this->call('POST', $this->apiPrefix . '/users', ['email' => 'newUser@example.com', 'password' => '1234']);

    $this->assertResponseOk();

    $this->assertJsonStringEqualsJsonString(
      '{"code": 200, "data": {"id":1, "email": "user@example.com"}}',
      $response->getContent()
    );
  }

  public function test_updating_user () {
    $mock = $this->setupMock();
    
    $mock->shouldReceive('update')->once()->with([
      'id'       => 1,
      'email'    => 'updatedUser@example.com',
      'password' => '12345'
    ])->andReturn([
      'id'    => 1,
      'email' => 'updatedUser@example.com'
    ]);

    $response = $this->call('PUT', $this->apiPrefix . '/users/1', ['email' => 'updatedUser@example.com', 'password' => '12345']);

    $this->assertResponseOk();

    $this->assertJsonStringEqualsJsonString(
      '{"code": 200, "data": {"id":1, "email": "updatedUser@example.com"}}',
      $response->getContent()
    );
  }

  public function test_deleting_user () {
    $mock = $this->setupMock();

    $mock->shouldReceive('delete')->once()->with(1);

    $response = $this->call('DELETE', $this->apiPrefix . '/users/1');

    $this->assertResponseOk();
  }

  // Tests for wrong data

  public function test_getting_single_user_with_wrong_id () {
    $mock = $this->setupMock();
    
    $mock->shouldReceive('findById')->with('foo')->once()->andThrow(new Devsave\Exceptions\UserNotFoundException);

    $response = $this->call('GET', $this->apiPrefix . '/users/foo');

    $this->assertResponseStatus(404);

    $this->assertJsonStringEqualsJsonString(
      '{"code": 404, "data": {"message": "User with id foo not found."}}',
      $response->getContent()
    );
  }

  public function test_saving_user_without_data () {
    $mock = $this->setupMock();

    $mock->shouldReceive('create')->never();
    
    $response = $this->call('POST', $this->apiPrefix . '/users');

    $this->assertResponseStatus(400);

    $this->assertJsonStringEqualsJsonString(
      '{"code": 400, "data": {"message": "Not enough data. Email and password are required."}}',
      $response->getContent()
    );
  }

  public function test_updating_user_with_wrong_data () {
    $mock = $this->setupMock();
    
    $mock->shouldReceive('update')->with([
      'id'       => 100,
      'email'    => 'updatedUser@example.com',
      'password' => '12345'
    ])->once()->andThrow('Devsave\Exceptions\UserNotFoundException');

    $response = $this->call('PUT', $this->apiPrefix . '/users/100', ['email' => 'updatedUser@example.com', 'password' => '12345']);

    $this->assertResponseStatus(404);

    $this->assertJsonStringEqualsJsonString(
      '{"code": 404, "data": {"message": "User with id 100 not found."}}',
      $response->getContent()
    );
  }

  public function test_deleting_user_with_wrong_id () {
    $mock = $this->setupMock();

    $mock->shouldReceive('delete')->with('foo')->once()->andThrow(new Devsave\Exceptions\UserNotFoundException);

    $response = $this->call('DELETE', $this->apiPrefix . '/users/foo');

    $this->assertResponseStatus(404);

    $this->assertJsonStringEqualsJsonString(
      '{"code": 404, "data": {"message": "User with id foo not found."}}',
      $response->getContent()
    ); 
  }

  public function setupMock () {
    Auth::shouldReceive('guest')->andReturn(false);

    $userRepositoryMock = m::mock('Devsave\Users\EloquentUsersRepository');

    App::instance('Devsave\Users\UsersInterface', $userRepositoryMock);

    return $userRepositoryMock;
  }
}