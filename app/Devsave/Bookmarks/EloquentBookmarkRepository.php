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
    return $this->bookmark->where('user_id', $userId)->get()->toArray();
  }

  public function findById ($id) {
    $bookmark = $this->bookmark->find($id);

    if (!$bookmark) {
      throw new BookmarkNotFoundException;
    }

    return $bookmark->toArray();
  }

  public function findByTag ($userId, $tagSlug) {
    return $this->tag->getBookmarks($userId, $tagSlug);
  }

  public function findByFolder ($userId, $folderSlug) {
   $folder = $this->folder->findBySlug($userId, $folderSlug);

   return $this->bookmark->where('folder_id', $folder['id'])->get()->toArray();
  }

  public function create ($bookmarkData) {
    if (!array_key_exists('user_id',$bookmarkData)) {
      throw new UserNotFoundException('User id not provided');
    } else if (!$this->user->find($bookmarkData['user_id'])) {
      throw new UserNotFoundException;
    }

    $bookmark = $this->bookmark->create([
      'url' => $bookmarkData['url'],
      'title' => $bookmarkData['title'],
      'notes' => $bookmarkData['notes'],
      'user_id' => $bookmarkData['user_id']
    ]);

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

    if (!array_key_exists('user_id',$bookmarkData)) {
      throw new UserNotFoundException('User id not provided');
    } else if (!$this->user->find($bookmarkData['user_id'])) {
      throw new UserNotFoundException;
    }

    $bookmark = $this->bookmark->find($bookmarkData['id']);

    if (!$bookmark) {
      throw new BookmarkNotFoundException;
    }

    $bookmark->update([
      'url' => $bookmarkData['url'],
      'title' => $bookmarkData['title'],
      'notes' => $bookmarkData['notes'],
      'user_id' => $bookmarkData['user_id']
    ]);

    if (array_key_exists('folder_id', $bookmarkData)) {
      $bookmark->folder()->associate($bookmarkData['folder_id']);
    }

    if (array_key_exists('tags', $bookmarkData)) {
      $this->updateTags($bookmark, $bookmarkData['tags']);
    }

    return $bookmark->toArray();
  }

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

    $bookmark->delete();
  }
}