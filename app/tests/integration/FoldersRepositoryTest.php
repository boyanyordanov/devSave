<?php

class FoldersRepositoryTest extends TestCase {

	protected $foldersRepo;

  public function setUp () {
    parent::setUp();

    // Migrate the database
    Artisan::call('migrate');

    // Create some records to work with
    
    Artisan::call('db:seed', ['--class' => 'TestingSeeder']);

    // Get an instance of the repository
    $this->foldersRepo = App::make('Devsave\Folders\FoldersInterface');
  }

  public function test_retrieving_folders_for_user () {
    $results = $this->foldersRepo->findByUser(1);

    $this->assertCount(4, $results);
    

    foreach ($results as $folder) {
      $this->assertArrayHasKey('id', $folder);
      $this->assertArrayHasKey('user_id', $folder);
      $this->assertArrayHasKey('name', $folder);
      $this->assertArrayHasKey('slug', $folder);
    }
  }

  public function test_retrieval_by_id () {
    $folder = $this->foldersRepo->findById(1);

    $this->assertArrayHasKey('id', $folder);
    $this->assertArrayHasKey('user_id', $folder);
    $this->assertArrayHasKey('name', $folder);
    $this->assertArrayHasKey('slug', $folder);

    $this->assertEquals(1, $folder['id']);
    $this->assertEquals(1, $folder['user_id']);
  }

  public function test_retrieval_by_slug () {
    $folder = $this->foldersRepo->findBySlug(1, 'first-folder');

    $this->assertArrayHasKey('id', $folder);
    $this->assertArrayHasKey('user_id', $folder);
    $this->assertArrayHasKey('name', $folder);
    $this->assertArrayHasKey('slug', $folder);

    $this->assertEquals(1, $folder['id']);
    $this->assertEquals(1, $folder['user_id']);
  }

  public function test_retrieval_by_name () {
    $folder = $this->foldersRepo->findByName(1, 'First folder');

    $this->assertArrayHasKey('id', $folder);
    $this->assertArrayHasKey('user_id', $folder);
    $this->assertArrayHasKey('name', $folder);
    $this->assertArrayHasKey('slug', $folder);

    $this->assertEquals(1, $folder['id']);
    $this->assertEquals(1, $folder['user_id']);
  }

  public function test_creating_folder () {
    $newFolder = $this->foldersRepo->create([
      'user_id' => 1,
      'name'    => 'New folder'
    ]);

    $this->assertArrayHasKey('id', $newFolder);
    $this->assertArrayHasKey('user_id', $newFolder);
    $this->assertArrayHasKey('name', $newFolder);
    $this->assertArrayHasKey('slug', $newFolder);

    $this->assertEquals(5, $newFolder['id']);
    $this->assertEquals(1, $newFolder['user_id']); 
    $this->assertEquals('New folder', $newFolder['name']); 
    $this->assertEquals('new-folder', $newFolder['slug']); 
  }

  public function test_updating_folder () {
    $updatedFolder = $this->foldersRepo->update([
      'user_id' => 1,
      'id' => 1,
      'name'    => 'New folder name'
    ]);

    $this->assertArrayHasKey('id', $updatedFolder);
    $this->assertArrayHasKey('user_id', $updatedFolder);
    $this->assertArrayHasKey('name', $updatedFolder);
    $this->assertArrayHasKey('slug', $updatedFolder);

    $this->assertEquals(1, $updatedFolder['id']);
    $this->assertEquals(1, $updatedFolder['user_id']); 
    $this->assertEquals('New folder name', $updatedFolder['name']); 
    $this->assertEquals('new-folder-name', $updatedFolder['slug']); 
  }

  /**
   * @expectedException Devsave\Exceptions\FolderNotFoundException
   */
  public function test_deleting_folder () {
    $this->foldersRepo->delete(1);

    $this->assertCount(3, $this->foldersRepo->findByUser(1));

    $this->foldersRepo->findById(1);
  }

  public function test_retrieving_bookmarks_for_folder () {
    $bookmarks = $this->foldersRepo->getBookmarks(1, 'first-folder');

    $this->assertCount(3, $bookmarks);

    foreach ($bookmarks as $bookmark) {
      $this->assertArrayHasKey('id', $bookmark);
      $this->assertArrayHasKey('url', $bookmark);
    }
  }

  public function test_counting_of_bookmarks_for_folder () {
    $count = $this->foldersRepo->getTotalBookmarks(1, 'first-folder');
    
    $this->assertEquals(3, $count);
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_querying_for_non_existent_user () {
    $this->foldersRepo->findByUser(100);
  }

  /**
   * @expectedException Devsave\Exceptions\FolderNotFoundException
   */
  public function test_querying_for_non_existent_folder () {
    $this->foldersRepo->findById(100);
  }

  /**
   * @expectedException Devsave\Exceptions\FolderNotFoundException
   */
  public function test_querying_with_slug_for_non_existent_folder () {
    $this->foldersRepo->findBySlug(1, 'some-slug');
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_querying_with_slug_for_non_existent_user () {
    $this->foldersRepo->findBySlug(100, 'first-folder');
  }

  /**
   * @expectedException Devsave\Exceptions\FolderNotFoundException
   */
  public function test_querying_with_name_for_non_existent_folder () {
    $this->foldersRepo->findByName(1, 'Some folder');
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_querying_with_name_for_non_existent_user () {
    $this->foldersRepo->findByName(100, 'First folder');
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_creating_folder_with_non_existent_user () {
    $newFolder = $this->foldersRepo->create([
      'user_id' => 100,
      'name'    => 'New folder'
    ]);
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_updating_folder_with_non_existent_user () {
    $newFolder = $this->foldersRepo->update([
      'id'      => 1,
      'user_id' => 100,
      'name'    => 'New folder'
    ]);
  }

  /**
   * @expectedException Devsave\Exceptions\FolderNotFoundException
   */
  public function test_updating_non_existend_folder () {
    $newFolder = $this->foldersRepo->update([
      'id'      => 100,
      'user_id' => 1,
      'name'    => 'New folder'
    ]);
  }

  /**
   * @expectedException Devsave\Exceptions\FolderNotFoundException
   */
  public function test_deleting_non_existend_folder () {
    $this->foldersRepo->delete(100);
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_bookmark_retrieval_for_non_existing_user () {
    $this->foldersRepo->getBookmarks(100, 'first-folder');
  }

  /**
   * @expectedException Devsave\Exceptions\FolderNotFoundException
   */
  public function test_bookmark_retrieval_for_non_existing_folder () {
    $this->foldersRepo->getBookmarks(1, 'Foo');
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_getting_total_for_non_existing_user () {
    $this->foldersRepo->getTotalBookmarks(100, 'first-folder');
  }

  /**
   * @expectedException Devsave\Exceptions\FolderNotFoundException
   */
  public function test_getting_total_for_non_existing_folder () {
    $this->foldersRepo->getTotalBookmarks(1, 'Foo');
  }

  public function test_adding_bookmark_to_folder () {
    $this->foldersRepo->addBookmark(1, 'first-folder', 1);
    
    $this->assertEquals(1, Bookmark::find(1)->folder_id);
  }

  /**
   * @expectedException Devsave\Exceptions\FolderNotFoundException
   */
  public function test_adding_bookmark_to_non_existing_folder () {
    $this->foldersRepo->addBookmark(1, 'foo', 1);
  }

  /**
   * @expectedException Devsave\Exceptions\UserNotFoundException
   */
  public function test_adding_bookmark_to_non_existing_user () {
    $this->foldersRepo->addBookmark(100, 'first-folder', 1);
  }

  /**
   * @expectedException Devsave\Exceptions\BookmarkNotFoundException
   */
  public function test_adding_non_existing_bookmark_to_folder () {
    $this->foldersRepo->addBookmark(1, 'first-folder', 100);
  }
}