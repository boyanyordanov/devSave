<?php namespace Devsave\Folders;

use Folder, User, Bookmark, Str;

use Devsave\Exceptions\FolderNotFoundException;
use Devsave\Exceptions\UserNotFoundException;
use Devsave\Exceptions\BookmarkNotFoundException;

class EloquentFoldersRepository implements FoldersInterface {
    
  protected $folder;

  protected $user;

  protected $bookmark;

  public function __construct (Folder $folder, User $user, Bookmark $bookmark) {
    $this->folder = $folder;

    $this->user = $user;

    $this->bookmark = $bookmark;
  }

  public function findByUser ($userId) {
    $user = $this->user->find($userId);

    if (!$user) {
      throw new UserNotFoundException;
    }

    return $user->folders->toArray();
  }
  
  public function findById ($id) {
    $folder = $this->folder->find($id);

    if (!$folder) {
      throw new FolderNotFoundException;
    }

    return $folder->toArray();
  }

  public function findBySlug ($userId, $slug) {
    $user = $this->user->find($userId);

    if (!$user) {
      throw new UserNotFoundException;
    }

    $folder = $user->folders()->where('slug', $slug)->first();

    if (!$folder) {
      throw new FolderNotFoundException;
    }

    return $folder->toArray();
  }

  public function findByName ($userId, $name) {
    $user = $this->user->find($userId);

    if (!$user) {
      throw new UserNotFoundException;
    }

    $folder = $user->folders()->where('name', $name)->first();

    if (!$folder) {
      throw new FolderNotFoundException;
    }

    return $folder->toArray();
  }

  public function addBookmark ($userId, $folderSlug, $bookmarkId) {
    if (!$this->user->find($userId)) {
      throw new UserNotFoundException;
    }

    $folder = $this->folder->where('user_id', $userId)->where('slug', $folderSlug)->first();

    if (!$folder) {
      throw new FolderNotFoundException;
    }

    $bookmark = $this->bookmark->find($bookmarkId);

    if (!$bookmark) {
      throw new BookmarkNotFoundException;
    }

    $folder->bookmarks()->save($bookmark);
  }
  
  public function create ($folderData) {
    if (!$this->user->find($folderData['user_id'])) {
      throw new UserNotFoundException;
    }

    $slug = $this->makeSlug($folderData['name'], $folderData['user_id']);

    $folder = $this->folder->create([
      'name'    => $folderData['name'],
      'slug'    => $slug,
      'user_id' => $folderData['user_id']
    ]);

    return $folder->toArray();
  }

  public function update ($folderData) {
    if (!$this->user->find($folderData['user_id'])) {
      throw new UserNotFoundException;
    }

    $folder = $this->folder->find($folderData['id']);

    if (!$folder) {
      throw new FolderNotFoundException;
    }

    $slug = $this->makeSlug($folderData['name'], $folderData['user_id']);

    $folder->name = $folderData['name'];
    $folder->slug = $slug;

    $folder->save();

    return $folder->toArray();
  }

  public function delete ($id) {
    $folder = $this->folder->find($id);

    if (!$folder) {
      throw new FolderNotFoundException;
    }

    $folder->delete();
  }

  protected function makeSlug ($name, $userId) {
    $slug = Str::slug($name);

    $foldersWithSlug = $this->folder->where('user_id', $userId)->where('slug', $slug)->count();

    if ($foldersWithSlug > 0) {
      $newNumber = $foldersWithSlug + 1;
      $slug = $slug . '-' . $newNumber;
    }
    
    return $slug;
  }

  public function getBookmarks($userId, $folderSlug) {
    if (!$this->user->find($userId)) {
      throw new UserNotFoundException;
    }

    $folder = $this->folder->where('user_id', $userId)->where('slug', $folderSlug)->first();

    if (!$folder) {
      throw new FolderNotFoundException;
    }

    return $folder->bookmarks->toArray();
  }

  public function getTotalBookmarks($userId, $folderSlug) {
    if (!$this->user->find($userId)) {
      throw new UserNotFoundException;
    }

    $folder = $this->folder->where('user_id', $userId)->where('slug', $folderSlug)->first();

    if (!$folder) {
      throw new FolderNotFoundException;
    }

    return $folder->bookmarks->count();
  }
}