<?php namespace Devsave\Bookmarks;

use User, Bookmark;

use Devsave\Tags\TagsInterface;
use Devsave\Folders\FoldersInterface;
use Devsave\Exceptions\UserNotFoundException;
use Devsave\Exceptions\BookmarkNotFoundException;

class EloquentBookmarkRepository implements BookmarkInterface {

  protected $bookmark;

  protected $tag;

  protected $folder;

  protected $user;

  public function __construct (Bookmark $bookmark, User $user, TagsInterface $tag, FoldersInterface $folder) {
    $this->bookmark = $bookmark;

    $this->user = $user;

    $this->tag = $tag;

    $this->folder = $folder;
  }

  public function findByUser ($userId) {
    $this->checkUser($userId);

    return $this->bookmark->where('user_id', $userId)->get()->toArray();
  }

  public function findById ($id) {
    $bookmark = $this->bookmark->with('tags', 'folder')->find($id);

    if (!$bookmark) {
      throw new BookmarkNotFoundException;
    }

    return $bookmark->toArray();
  }

  public function findByTag ($userId, $tagSlug) {
    $this->checkUser($userId);

    return $this->tag->getBookmarks($userId, $tagSlug);
  }

  public function findByFolder ($userId, $folderSlug) {
   $this->checkUser($userId);

   return $this->folder->getBookmarks($userId, $folderSlug);
  }

  public function create ($bookmarkData) {
    if (!array_key_exists('user_id',$bookmarkData)) {
      throw new UserNotFoundException('User id not provided');
    } else {
      $this->checkUser($bookmarkData['user_id']);
    }

    $data = [
      'url' => $bookmarkData['url'],
      'user_id' => $bookmarkData['user_id']
    ];

    if (array_key_exists('title', $bookmarkData)) {
      $data['title'] = $bookmarkData['title'];
    }

    if (array_key_exists('notes', $bookmarkData)) {
      $data['notes'] = $bookmarkData['notes'];
    }

    $bookmark = $this->bookmark->create($data);

    if (array_key_exists('folder_id', $bookmarkData)) {
      $bookmark->folder()->associate($bookmarkData['folder_id']);
    }

    if (array_key_exists('tags', $bookmarkData)) {
      $this->updateTags($bookmark, $bookmarkData['tags']);
    }

    return $bookmark->toArray();
  }

  public function update ($bookmarkData) {
    if (!array_key_exists('id', $bookmarkData)) {
      throw new BookmarkNotFoundException;
    }

    $bookmark = $this->bookmark->find($bookmarkData['id']);

    if (!$bookmark) {
      throw new BookmarkNotFoundException;
    }

    $data = [];

    if (array_key_exists('url', $bookmarkData)) {
      $data['url'] = $bookmarkData['url'];
    }

    if (array_key_exists('title', $bookmarkData)) {
      $data['title'] = $bookmarkData['title'];
    }

    if (array_key_exists('notes', $bookmarkData)) {
      $data['notes'] = $bookmarkData['notes'];
    }

    $bookmark->update($data);

    if (array_key_exists('folder_id', $bookmarkData)) {
      $bookmark->folder()->associate($bookmarkData['folder_id']);
    }

    if (array_key_exists('tags', $bookmarkData)) {
      $this->updateTags($bookmark, $bookmarkData['tags']);
    }

    return $bookmark->toArray();
  }

  /**
   * Syncs the tags for a user with provided array of tag slugs.
   *   
   * @param  Bookmark model/array $bookmark
   * @param  Array $tagSlugs
   */
  protected function updateTags ($bookmark, $tagSlugs) {
    $tagIds = [];

    foreach ($tagSlugs as $tagSlug) {
      $tag = $this->tag->findBySlug($bookmark['user_id'], $tagSlug);

      $tagIds[] = $tag['id'];
    }
    
    $bookmark->tags()->sync($tagIds);
  }

  public function delete ($id) {
    $bookmark = $this->bookmark->find($id);

    if ($bookmark) {
      $bookmark->delete();
    }
  }

  /**
   * Checks if a user exists
   * @param  Integer $id
   * @throws Devsave\Exceptions\UserNotFoundException if a user with the provided id does not exists.
   */
  protected function checkUser ($id) {
    if (!$this->user->find($id)) {
      throw new UserNotFoundException;
    }
  }

  public function getTotal ($userId) {
    $this->checkUser($userId);

    return $this->user->find($userId)->bookmarks->count();
  }

  public function getTotalForFolder ($userId, $folderSlug) {
    $this->checkUser($userId);

    return $this->folder->getTotalBookmarks($userId, $folderSlug);
  }

  public function getTotalForTag ($userId, $tagSlug) {
    $this->checkUser($userId);

    return $this->tag->getTotalBookmarks($userId, $tagSlug);
  } 
}