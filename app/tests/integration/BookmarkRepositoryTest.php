<?php

/**
 * TODO: add eager loading to all queries
 */
class BookmarkRepositoryTest extends TestCase {

  protected $bookmarkRepo;

  public function setUp () {
    parent::setUp();

    // Migrate the database
    Artisan::call('migrate');

    // Create some records to work with
    
    Artisan::call('db:seed', ['--class' => 'TestingSeeder']);

    // Get an instance of the repository
    $this->bookmarkRepo = App::make('Devsave\Bookmarks\BookmarkInterface');
  }

  public function test_single_bookmark_retrieval () {
    $bookmark = $this->bookmarkRepo->findById(1);

    $this->assertEquals(1, $bookmark['id']);

    $this->assertArrayHasKey('url', $bookmark);
    $this->assertArrayHasKey('title', $bookmark);
    $this->assertArrayHasKey('notes', $bookmark);
    $this->assertArrayHasKey('user_id', $bookmark);
    $this->assertArrayHasKey('folder_id', $bookmark);
  }

  /**
   * @expectedException Devsave\Exceptions\BookmarkNotFoundException
   */
  public function test_wrong_bookmark_id () {
    $this->bookmarkRepo->findById(100);
  }

  public function test_bookmark_without_folder () {
    $bookmark = $this->bookmarkRepo->findById(1);

    $this->assertEquals(0, $bookmark['folder_id']);
  }

  public function test_bookmark_retrieval_by_user () {
    $bookmarks = $this->bookmarkRepo->findByUser(1);

    $this->assertCount(9, $bookmarks);

    // Test if the retrieved results are valid bookmarks
    foreach($bookmarks as $bookmark) {
      $this->assertArrayHasKey('url', $bookmark);
      $this->assertArrayHasKey('title', $bookmark);
      $this->assertArrayHasKey('notes', $bookmark);
      $this->assertArrayHasKey('user_id', $bookmark);
      $this->assertArrayHasKey('folder_id', $bookmark);
    }
  }

  public function test_retrieval_by_folder () {
    $bookmarks = $this->bookmarkRepo->findByFolder(1, 'first-folder');

    $this->assertCount(3, $bookmarks);

    // Test if th retrieved results are valid bookmarks
    foreach($bookmarks as $bookmark) {
      $this->assertArrayHasKey('url', $bookmark);
      $this->assertArrayHasKey('title', $bookmark);
      $this->assertArrayHasKey('notes', $bookmark);
      $this->assertArrayHasKey('user_id', $bookmark);
      $this->assertArrayHasKey('folder_id', $bookmark);

      // Check if the folder is correct
      $this->assertEquals(1, $bookmark['folder_id']);
    }
  }

  public function test_retrieval_by_tag () {
    $bookmarks = $this->bookmarkRepo->findByTag(1, 'first-tag');

    $this->assertCount(6, $bookmarks);

    // Test if the retrieved results are valid bookmarks
    foreach($bookmarks as $bookmark) {
      $this->assertArrayHasKey('url', $bookmark);
      $this->assertArrayHasKey('title', $bookmark);
      $this->assertArrayHasKey('notes', $bookmark);
      $this->assertArrayHasKey('user_id', $bookmark);
      $this->assertArrayHasKey('folder_id', $bookmark);
    }
  }

  public function test_creating_bookmark_without_folder () {
    $bookmarkData = [
      'url'       => 'http://test-site.dev',
      'title'     => 'Test site',
      'notes'     => 'Notes for the test site',
      'user_id'   => 1,
      'tags'      => ['first-tag'] 
    ];

    $createdBookmark = $this->bookmarkRepo->create($bookmarkData);

    $this->assertEquals(10, $createdBookmark['id']);
    $this->assertEquals($bookmarkData['url'], $createdBookmark['url']);
    $this->assertEquals($bookmarkData['title'], $createdBookmark['title']);
    $this->assertEquals($bookmarkData['notes'], $createdBookmark['notes']);
    $this->assertEquals($bookmarkData['user_id'], $createdBookmark['user_id']);
  }

  public function test_creating_bookmark_without_tags () {
    $bookmarkData = [
      'url'       => 'http://test-site.dev',
      'title'     => 'Test site',
      'notes'     => 'Notes for the test site',
      'user_id'   => 1 
    ];

    $createdBookmark = $this->bookmarkRepo->create($bookmarkData);

    $this->assertEquals(10, $createdBookmark['id']);
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_creating_bookmark_without_user () {
    $bookmarkData = [
      'url'       => 'http://test-site.dev',
      'title'     => 'Test site',
      'notes'     => 'Notes for the test site'
    ];

    $createdBookmark = $this->bookmarkRepo->create($bookmarkData);
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_creating_bookmark_with_wrong_user () {
    $bookmarkData = [
      'url'     => 'http://test-site.dev',
      'title'   => 'Test site',
      'notes'   => 'Notes for the test site',
      'user_id' => 100
    ];

    $createdBookmark = $this->bookmarkRepo->create($bookmarkData);
  }

  public function test_updating_bookmark_without_folder () {
    $bookmarkData = [
      'id'        => 1,
      'url'       => 'http://test-site.dev',
      'title'     => 'Test site',
      'notes'     => 'Notes for the test site',
      'user_id'   => 1,
      'tags'      => ['first-tag'] 
    ];

    $updatedBookmark = $this->bookmarkRepo->update($bookmarkData);

    $this->assertEquals(1, $updatedBookmark['id']);
    $this->assertEquals($bookmarkData['url'], $updatedBookmark['url']);
    $this->assertEquals($bookmarkData['title'], $updatedBookmark['title']);
    $this->assertEquals($bookmarkData['notes'], $updatedBookmark['notes']);
    $this->assertEquals($bookmarkData['user_id'], $updatedBookmark['user_id']);
  }

  public function test_updating_bookmark_without_tags () {
    $bookmarkData = [
      'id'        => 1,
      'url'       => 'http://test-site.dev',
      'title'     => 'Test site',
      'notes'     => 'Notes for the test site',
      'user_id'   => 1 
    ];

    $updatedBookmark = $this->bookmarkRepo->update($bookmarkData);

    $this->assertEquals(1, $updatedBookmark['id']);
    $this->assertEquals($bookmarkData['url'], $updatedBookmark['url']);
  }


  /**
   * @expectedException Devsave\Exceptions\BookmarkNotFoundException
   */
  public function test_updating_bookmark_without_id () {
    $bookmarkData = [
      'url'       => 'http://test-site.dev',
      'title'     => 'Test site',
      'notes'     => 'Notes for the test site'
    ];

    $updatedBookmark = $this->bookmarkRepo->update($bookmarkData);
  }
  
  public function test_deleting_bookmark () {
    $this->bookmarkRepo->delete(1);

    $this->assertEquals(8, Bookmark::count());
    $this->assertNull(Bookmark::find(1));
  }

  public function test_get_total_bookmarks_for_user () {
    $count = $this->bookmarkRepo->getTotal(1);

    $this->assertEquals(9, $count);
  }

  public function test_get_total_bookmarks_for_folder () {
    $count = $this->bookmarkRepo->getTotalForFolder(1, 'first-folder');

    $this->assertEquals(3, $count);
  }

  public function test_get_total_bookmarks_for_tag () {
    $count = $this->bookmarkRepo->getTotalForTag(1, 'first-tag');

    $this->assertEquals(6, $count);
  }
}