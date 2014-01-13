<?php

class TagsRepositoryTest extends TestCase {

  protected $tagsRepo;

  public function setUp () {
    parent::setUp();

    // Migrate the database
    Artisan::call('migrate');

    // Create some records to work with
    
    Artisan::call('db:seed', ['--class' => 'TestingSeeder']);

    // Get an instance of the repository
    $this->tagsRepo = App::make('Devsave\Tags\TagsInterface');
  }

  public function test_retrieving_tags_for_user () {
    $results = $this->tagsRepo->findByUser(1);

    $this->assertCount(4, $results);
    

    foreach ($results as $tag) {
      $this->assertArrayHasKey('id', $tag);
      $this->assertArrayHasKey('user_id', $tag);
      $this->assertArrayHasKey('name', $tag);
      $this->assertArrayHasKey('slug', $tag);
    }
  }

  public function test_retrieval_by_id () {
    $tag = $this->tagsRepo->findById(1);

    $this->assertArrayHasKey('id', $tag);
    $this->assertArrayHasKey('user_id', $tag);
    $this->assertArrayHasKey('name', $tag);
    $this->assertArrayHasKey('slug', $tag);

    $this->assertEquals(1, $tag['id']);
    $this->assertEquals(1, $tag['user_id']);
  }

  public function test_retrieval_by_slug () {
    $tag = $this->tagsRepo->findBySlug(1, 'first-tag');

    $this->assertArrayHasKey('id', $tag);
    $this->assertArrayHasKey('user_id', $tag);
    $this->assertArrayHasKey('name', $tag);
    $this->assertArrayHasKey('slug', $tag);

    $this->assertEquals(1, $tag['id']);
    $this->assertEquals(1, $tag['user_id']);
  }

  public function test_retrieval_by_name () {
    $tag = $this->tagsRepo->findByName(1, 'First tag');

    $this->assertArrayHasKey('id', $tag);
    $this->assertArrayHasKey('user_id', $tag);
    $this->assertArrayHasKey('name', $tag);
    $this->assertArrayHasKey('slug', $tag);

    $this->assertEquals(1, $tag['id']);
    $this->assertEquals(1, $tag['user_id']);
  }

  public function test_creating_tag () {
    $newTag = $this->tagsRepo->create([
      'user_id' => 1,
      'name'    => 'New tag'
    ]);

    $this->assertArrayHasKey('id', $newTag);
    $this->assertArrayHasKey('user_id', $newTag);
    $this->assertArrayHasKey('name', $newTag);
    $this->assertArrayHasKey('slug', $newTag);

    $this->assertEquals(5, $newTag['id']);
    $this->assertEquals(1, $newTag['user_id']); 
    $this->assertEquals('New tag', $newTag['name']); 
    $this->assertEquals('new-tag', $newTag['slug']); 
  }

  public function test_updating_tag () {
    $updatedTag = $this->tagsRepo->update([
      'user_id' => 1,
      'id' => 1,
      'name'    => 'New tag name'
    ]);

    $this->assertArrayHasKey('id', $updatedTag);
    $this->assertArrayHasKey('user_id', $updatedTag);
    $this->assertArrayHasKey('name', $updatedTag);
    $this->assertArrayHasKey('slug', $updatedTag);

    $this->assertEquals(1, $updatedTag['id']);
    $this->assertEquals(1, $updatedTag['user_id']); 
    $this->assertEquals('New tag name', $updatedTag['name']); 
    $this->assertEquals('new-tag-name', $updatedTag['slug']); 
  }

  /**
   * @expectedException Devsave\Exceptions\TagNotFoundException
   */
  public function test_deleting_tag () {
    $this->tagsRepo->delete(1);

    $this->assertCount(3, $this->tagsRepo->findByUser(1));

    $this->tagsRepo->findById(1);
  }

  public function test_retrieving_bookmarks_for_tag () {
    $bookmarks = $this->tagsRepo->getBookmarks(1, 'first-tag');

    $this->assertCount(6, $bookmarks);

    foreach ($bookmarks as $bookmark) {
      $this->assertArrayHasKey('id', $bookmark);
      $this->assertArrayHasKey('url', $bookmark);
    }
  }

  public function test_counting_of_bookmarks_for_tag () {
    $count = $this->tagsRepo->getTotalBookmarks(1, 'first-tag');
    
    $this->assertEquals(6, $count);
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_querying_for_non_existent_user () {
    $this->tagsRepo->findByUser(100);
  }

  /**
   * @expectedException Devsave\Exceptions\TagNotFoundException
   */
  public function test_querying_for_non_existent_tag () {
    $this->tagsRepo->findById(100);
  }

  /**
   * @expectedException Devsave\Exceptions\TagNotFoundException
   */
  public function test_querying_with_slug_for_non_existent_tag () {
    $this->tagsRepo->findBySlug(1, 'some-slug');
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_querying_with_slug_for_non_existent_user () {
    $this->tagsRepo->findBySlug(100, 'first-tag');
  }

  /**
   * @expectedException Devsave\Exceptions\TagNotFoundException
   */
  public function test_querying_with_name_for_non_existent_tag () {
    $this->tagsRepo->findByName(1, 'Some tag');
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_querying_with_name_for_non_existent_user () {
    $this->tagsRepo->findByName(100, 'First tag');
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_creating_tag_with_non_existent_user () {
    $newTag = $this->tagsRepo->create([
      'user_id' => 100,
      'name'    => 'New tag'
    ]);
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_updating_tag_with_non_existent_user () {
    $newTag = $this->tagsRepo->update([
      'id'      => 1,
      'user_id' => 100,
      'name'    => 'New tag'
    ]);
  }

  /**
   * @expectedException Devsave\Exceptions\TagNotFoundException
   */
  public function test_updating_non_existend_tag () {
    $newTag = $this->tagsRepo->update([
      'id'      => 100,
      'user_id' => 1,
      'name'    => 'New tag'
    ]);
  }

  /**
   * @expectedException Devsave\Exceptions\TagNotFoundException
   */
  public function test_deleting_non_existend_tag () {
    $this->tagsRepo->delete(100);
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_bookmark_retrieval_for_non_existing_user () {
    $this->tagsRepo->getBookmarks(100, 'first-tag');
  }

  /**
   * @expectedException Devsave\Exceptions\TagNotFoundException
   */
  public function test_bookmark_retrieval_for_non_existing_tag () {
    $this->tagsRepo->getBookmarks(1, 'Foo');
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_getting_total_for_non_existing_user () {
    $this->tagsRepo->getTotalBookmarks(100, 'first-tag');
  }

  /**
   * @expectedException Devsave\Exceptions\TagNotFoundException
   */
  public function test_getting_total_for_non_existing_tag () {
    $this->tagsRepo->getTotalBookmarks(1, 'Foo');
  }
}