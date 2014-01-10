<?php

class BookmarkRepositoryTest extends TestCase {

  protected $bookmarkRepo;

  public function setUp () {
    parent::setUp();

    Artisan::call('migrate');
    Artisan::call('db:seed');

    $this->bookmarkRepo = App::make('Devsave\Bookmarks\BookmarkInterface');
  }

  public function test_single_bookmark_retrieval () {
    $bookmark = $this->bookmarkRepo->findById(1);

    $this->assertEquals(1, $bookmark['id']);
  }

  public function test_bookmark_retrieval_by_user () {
    $bookmarks = $this->bookmarkRepo->findByUser(1);

    // We presume that Eloquent is tested good enough so we are just testing if the repository is returning the right result set
    $bookmarksCount = User::find(1)->bookmarks->count();
   
    $bookmarksEloquent = User::find(1)->bookmarks->toArray();

    $this->assertCount($bookmarksCount, $bookmarks);
    $this->assertEquals($bookmarks, $bookmarksEloquent);
  }
}