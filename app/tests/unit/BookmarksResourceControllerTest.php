<?php

use Mockery as m;

class BookmarksResourceControllerTest extends TestCase {

  protected $apiPrefix = '/api/v1';
  
  protected $usersRepoMock;

  protected $bookmarksRepoMock;
  
  public function tearDown () {
    m::close();
  }

  public function setUp () {
    parent::setUp();

    Auth::shouldReceive('check')->andReturn(true);

    $this->usersRepoMock = m::mock('Devsave\Users\EloquentUsersRepository');

    App::instance('Devsave\Users\UsersInterface', $this->usersRepoMock);

    $this->bookmarksRepoMock = m::mock('Devsave\Bookmarks\EloquentBookmarkRepository');

    App::instance('Devsave\Bookmarks\BookmarkInterface', $this->bookmarksRepoMock);
  }

	public function test_retrieving_bookmarks ()
	{
    $this->bookmarksRepoMock->shouldReceive('findByUser')->once()->with(1)->andReturn([
      ['id' => 1, 'url' => 'http://devsave.dev', 'title' => 'Devsave', 'notes' => 'Notes for devsave', 'folder_id' => null, 'user_id' => 1],
      ['id' => 2, 'url' => 'http://github.com', 'title' => 'GitHub', 'notes' => 'Notes for github', 'folder_id' => 1, 'user_id' => 1],
      ['id' => 3, 'url' => 'http://tu-varna.bg', 'title' => 'TU-Varna', 'notes' => 'Notes for TU-Varna', 'folder_id' => 1, 'user_id' => 1]
    ]);

    $this->bookmarksRepoMock->shouldReceive('getTotal')->once()->with(1)->andReturn(3);

    $response = $this->call('GET', $this->apiPrefix . '/users/1/bookmarks');

    $this->assertResponseOk();

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 200, 
        "data": { 
          "total": 3,
          "items": [
            {"id": 1, "url": "http://devsave.dev", "title": "Devsave", "notes": "Notes for devsave", "folder_id": "", "user_id": 1},
            {"id": 2, "url": "http://github.com", "title": "GitHub", "notes": "Notes for github", "folder_id": 1, "user_id": 1},
            {"id": 3, "url": "http://tu-varna.bg", "title": "TU-Varna", "notes": "Notes for TU-Varna", "folder_id": 1, "user_id": 1}
          ]
        }
      }',
      $response->getContent()
    );
	}

  public function test_retrieving_single_bookmark ()
  {
    $this->bookmarksRepoMock->shouldReceive('findById')->once()->with(1)->andReturn([
      'id' => 1, 
      'url' => 'http://devsave.dev', 
      'title' => 'Devsave', 
      'notes' => 'Notes for devsave', 
      'folder_id' => 1, 
      'user_id' => 1
    ]);

    $this->usersRepoMock->shouldReceive('findById')->once()->andReturn('foo');

    $response = $this->call('GET', $this->apiPrefix . '/users/1/bookmarks/1');

    $this->assertResponseOk();

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 200, 
        "data": {
          "id": 1, 
          "url": "http://devsave.dev",
          "title": "Devsave", 
          "notes": "Notes for devsave", 
          "folder_id": 1, 
          "user_id": 1
        }
      }',
      $response->getContent()
    );
  }

  public function test_creating_bookmark () {
    $this->bookmarksRepoMock->shouldReceive('create')->once()->with([
      'user_id'   => 1,
      'url'       => 'http://devsave.dev',
      'title'     => 'Devsave',
      'notes'     => 'Notes for Devsave',
      'folder_id' => 1
    ])->andReturn([
      'id' => 1, 
      'url' => 'http://devsave.dev', 
      'title' => 'Devsave', 
      'notes' => 'Notes for Devsave', 
      'folder_id' => 1, 
      'user_id' => 1
    ]);

    $response = $this->call('POST', $this->apiPrefix . '/users/1/bookmarks', [
      'url'       => 'http://devsave.dev',
      'title'     => 'Devsave',
      'notes'     => 'Notes for Devsave',
      'folder_id' => 1
    ]);

    $this->assertResponseOk();

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 200, 
        "data": {
          "id": 1, 
          "url": "http://devsave.dev", 
          "title": "Devsave",
          "notes": "Notes for Devsave", 
          "folder_id": 1, 
          "user_id": 1
        }
      }',
      $response->getContent()
    ); 
  }

  public function test_updating_bookmark ()
  {
    $this->bookmarksRepoMock->shouldReceive('update')->once()->with([
      'id'        => 1,
      'user_id'   => 1,
      'url'       => 'http://devsave.dev',
      'title'     => 'Devsave.dev',
      'notes'     => 'Updated notes on Devsave',
      'folder_id' => 2
    ])->andReturn([
      'id' => 1, 
      'url' => 'http://devsave.dev', 
      'title' => 'Devsave.dev', 
      'notes' => 'Updated notes on Devsave', 
      'folder_id' => 2, 
      'user_id' => 1
    ]);

    $this->usersRepoMock->shouldReceive('findById')->andReturn('foo');

    $response = $this->call('PUT', $this->apiPrefix . '/users/1/bookmarks/1', [
      'user_id'   => 1,
      'url'       => 'http://devsave.dev',
      'title'     => 'Devsave.dev',
      'notes'     => 'Updated notes on Devsave',
      'folder_id' => 2
    ]);

    $this->assertResponseOk();

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 200, 
        "data": {
          "id": 1, 
          "url": "http://devsave.dev",
          "title": "Devsave.dev", 
          "notes": "Updated notes on Devsave", 
          "folder_id": 2, 
          "user_id": 1
        }
      }',
      $response->getContent()
    ); 
  }

  public function test_deleting_bookmark ()
  {
    $this->bookmarksRepoMock->shouldReceive('delete')->once()->with(1);

    $this->usersRepoMock->shouldReceive('findById')->once()->andReturn('foo');

    $response = $this->call('DELETE', $this->apiPrefix . '/users/1/bookmarks/1');

    $this->assertResponseOk();

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 200,
        "data": {
          "message": "Bookmark deleted successfully."
        }
      }',
      $response->getContent()
    );
  }

  // Tests for filtering
  
  public function test_retrieving_bookmarks_for_tag ()
  {
    

    $this->bookmarksRepoMock->shouldReceive('findByTag')->once()->with(1, 'foo')->andReturn([
      ['id' => 1, 'url' => 'http://i-have-this-tag.dev', 'user_id' => 1]
    ]);

    $this->bookmarksRepoMock->shouldReceive('getTotalForTag')->once()->with(1, 'foo')->andReturn(1);

    $response = $this->call('GET', $this->apiPrefix . '/users/1/bookmarks?tag=foo');

    $this->assertResponseOk();

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 200,
        "data": {
          "total": 1,
          "items": [
            {
              "id": 1,
              "url": "http://i-have-this-tag.dev",
              "user_id": 1
            }
          ]
        }
      }',
      $response->getContent()
    );
  } 

  public function test_retrieving_bookmarks_for_folder ()
  {
    $this->bookmarksRepoMock->shouldReceive('findByFolder')->once()->with(1, 'foo')->andReturn([
      ['id' => 1, 'url' => 'http://i-have-this-tag.dev', 'user_id' => 1]
    ]);

    $this->bookmarksRepoMock->shouldReceive('getTotalForFolder')->once()->with(1, 'foo')->andReturn(1);

    $response = $this->call('GET', $this->apiPrefix . '/users/1/bookmarks?folder=foo');

    $this->assertResponseOk();

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 200,
        "data": {
          "total": 1,
          "items": [
            {
              "id": 1,
              "url": "http://i-have-this-tag.dev",
              "user_id": 1
            }
          ]
        }
      }',
      $response->getContent()
    );
  } 

  // Tests for wrong data
  
  // Failing requests getting bookmarks for user 
  public function test_getting_bookmarks_for_user_with_wrong_id () {
    $this->bookmarksRepoMock->shouldReceive('findByUser')->with(100)->once()->andThrow(new Devsave\Exceptions\UserNotFoundException);

    $response = $this->call('GET', $this->apiPrefix . '/users/100/bookmarks');

    $this->assertResponseStatus(400);

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 400, 
        "data": {
          "message": "Cannot retrieve bookmarks for non existing user with id 100."
        }
      }',
      $response->getContent()
    );
  }

  // Failing requests for getting single bookmark
  public function test_getting_single_bookmark_with_wrong_id () {
    $this->bookmarksRepoMock->shouldReceive('findById')->with('foo')->once()->andThrow(new Devsave\Exceptions\BookmarkNotFoundException);

    $this->usersRepoMock->shouldReceive('findById')->once()->andReturn('foo');

    $response = $this->call('GET', $this->apiPrefix . '/users/1/bookmarks/foo');

    $this->assertResponseStatus(404);

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 404, 
        "data": {
          "message": "Bookmark with id foo not found."
        }
      }',
      $response->getContent()
    );
  }

  public function test_getting_single_bookmark_with_wrong_user_id () {
    $this->bookmarksRepoMock->shouldReceive('findById')->with(1)->never();

    $this->usersRepoMock->shouldReceive('findById')->once()->andThrow(new Devsave\Exceptions\UserNotFoundException);

    $response = $this->call('GET', $this->apiPrefix . '/users/foo/bookmarks/1');

    $this->assertResponseStatus(400);

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 400, 
        "data": {
          "message": "Cannot retrieve bookmark for non existing user with id foo."
        }
      }',
      $response->getContent()
    );
  }

  // Failing requests for creating a bookmark
  public function test_saving_bookmark_without_required_data () {
    $this->bookmarksRepoMock->shouldReceive('create')->never();
    
    $response = $this->call('POST', $this->apiPrefix . '/users/1/bookmarks');

    $this->assertResponseStatus(400);

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 400, 
        "data": {
          "message": "Not enough data. Url is required."
        }
      }',
      $response->getContent()
    );
  }

  public function test_saving_bookmark_for_non_existing_user () {
    

    $this->bookmarksRepoMock->shouldReceive('create')->andThrow(new Devsave\Exceptions\UserNotFoundException);
    
    $response = $this->call('POST', $this->apiPrefix . '/users/foo/bookmarks', ['url' => 'http://this-will-fail.dev']);

    $this->assertResponseStatus(400);

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 400, 
        "data": {
          "message": "Cannot create bookmark for non existing user."
        }
      }',
      $response->getContent()
    );
  }

  // Failing requests for updating bookmark
  public function test_updating_non_exising_bookmark () {
    $this->bookmarksRepoMock->shouldReceive('update')->with([
      'user_id' => 1,
      'id'      => 'foo',
      'url'     => 'http://new-url.dev',
      'title'=>NULL,
      'notes'=>NULL,
      'folder_id'=>NULL
    ])->once()->andThrow('Devsave\Exceptions\BookmarkNotFoundException');

    $this->usersRepoMock->shouldReceive('findById')->once()->andReturn('foo');

    $response = $this->call('PUT', $this->apiPrefix . '/users/1/bookmarks/foo', ['url' => 'http://new-url.dev']);

    $this->assertResponseStatus(404);

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 404, 
        "data": {
          "message": "Bookmark with id foo not found."
        }
      }',
      $response->getContent()
    );
  }

  public function test_updating_bookmark_with_missing_data () {
    $this->bookmarksRepoMock->shouldReceive('update')->never();

    $this->usersRepoMock->shouldReceive('findById')->andReturn('foo');

    $response = $this->call('PUT', $this->apiPrefix . '/users/1/bookmarks/1');

    $this->assertResponseStatus(400);

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 400,
        "data": {
          "message": "Not enough data. Bookmark url is required."
        }
      }',
      $response->getContent()
    );
  }

  public function test_updating_bookmark_for_non_existing_user () {
    $this->bookmarksRepoMock->shouldReceive('update')->with([
      'user_id' => 100,
      'id'      => 1,
      'url'     => 'http://user-is-missing.dev',
      'title'=>NULL,
      'notes'=>NULL,
      'folder_id'=>NULL
    ]);

    $this->usersRepoMock->shouldReceive('findById')->andThrow(new Devsave\Exceptions\UserNotFoundException);

    $response = $this->call('PUT', $this->apiPrefix . '/users/100/bookmarks/1', ['url' => 'http://user-is-missing.dev']);

    $this->assertResponseStatus(400);

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 400,
        "data": {
          "message": "Cannot update bookmark for non existing user."
        }
      }',
      $response->getContent()
    );
  }

  // Failing requests for deleting a bookmark
  public function test_deleting_bookmark_with_wrong_id () {
    

    $this->bookmarksRepoMock->shouldReceive('delete')->with('foo')->once()->andThrow(new Devsave\Exceptions\BookmarkNotFoundException);

    $this->usersRepoMock->shouldReceive('findById')->once()->andReturn('foo');

    $response = $this->call('DELETE', $this->apiPrefix . '/users/1/bookmarks/foo');

    $this->assertResponseStatus(404);

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 404, 
        "data": {
          "message": "Bookmark with id foo not found."
        }
      }',
      $response->getContent()
    ); 
  }

  public function test_deleting_bookmark_for_non_existing_user () {
    

    $this->bookmarksRepoMock->shouldReceive('delete')->never();

    $this->usersRepoMock->shouldReceive('findById')->once()->andThrow(new Devsave\Exceptions\UserNotFoundException);

    $response = $this->call('DELETE', $this->apiPrefix . '/users/foo/bookmarks/1');

    $this->assertResponseStatus(400);

    $this->assertJsonStringEqualsJsonString(
      '{
        "code": 400, 
        "data": {
          "message": "Wrong url format. User with id foo doesn\'t exist."
        }
      }',
      $response->getContent()
    ); 
  }

}