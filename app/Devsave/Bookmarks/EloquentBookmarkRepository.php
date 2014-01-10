<?php namespace Devsave\Bookmarks;

use User, Bookmark;

use Devsave\Tags\TagsInterface;
use Devsave\Folders\FoldersInterface;

class EloquentBookmarkRepository implements BookmarkInterface {

  protected $bookmark;

  protected $tag;

  protected $folder;

  public function __construct (Bookmark $bookmark, TagsInterface $tag, FoldersInterface $folder) {
    $this->bookmark = $bookmark;

    $this->tag = $tag;

    $this->folder = $folder;
  }

  public function findByUser ($userId) {
    return $this->bookmark->where('user_id', $userId)->get()->toArray();
  }

  public function findById ($id) {
    return $this->bookmark->find($id)->toArray();
  }

  public function findByTag ($userId, $tagSlug) {
    return $this->tag->findBySlug($userId, $tagSlug)->getBookmarks();
  }

  public function findByFolder ($userId, $folderSlug) {
   $folder = $this->folder->findBySlug($userId, $folderSlug);

   return $this->bookmark->where('folder_id', $folder['id'])->get()->toArray();
  }

  public function create ($bookmarkData) {
    $bookmark = $this->bookmark->create([
      'url' => $bookmarkData['url'],
      'title' => $bookmarkData['title'],
      'notes' => $bookmarkData['notes'],
      'user_id' => $bookmarkData['user_id']
    ]);

    if ($bookmarkData['folder_id']) {
      $bookmark->folder()->associate($bookmarkData['folder_id']);
    }

    if ($bookmarkData['tags']) {
      $this->updateTags($bookmark, $bookmarkData['tags']);
    }
  }

  public function update ($bookmarkData) {
    $bookmark = $this->bookmark->find($bookmarkData['id']);

    $bookmark->update([
      'url' => $bookmarkData['url'],
      'title' => $bookmarkData['title'],
      'notes' => $bookmarkData['notes'],
      'user_id' => $bookmarkData['user_id']
    ]);

    if ($bookmarkData['folder_id']) {
      $bookmark->folder()->associate($bookmarkData['folder_id']);
    }

    if ($bookmarkData['tags']) {
      $this->updateTags($bookmark, $bookmarkData['tags']);
    }
  }

  protected function updateTags ($bookmark, $tagSlugs) {
    $tagIds = [];

    foreach ($tagSlugs as $tagSlug) {
      $tag = $this->tag->findBySlug($bookmarkData['user_id'], $tagSlug);

      $tagIds[] = $tag['id'];
    }
    
    $bookmark->tags()->sync($tagIds);
  }

  public function delete ($id) {
    $bookmark = $this->bookmark->find($id);

    $bookmark->delete();
  }
}