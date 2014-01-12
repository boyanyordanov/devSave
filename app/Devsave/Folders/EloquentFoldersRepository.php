<?php namespace Devsave\Folders;

use Folder, Str;

use Devsave\Exceptions\FolderNotFoundException;

class EloquentFoldersRepository implements FoldersInterface {
    
  protected $folder;

  public function __construct (Folder $folder) {
    $this->folder = $folder;
  }

  public function findByUser ($userId) {
    return $this->folder->where('user_id', $userId)->get()->toArray();
  }
  
  public function findById ($id) {
    $folder = $this->folder->find($id);

    if (!$folder) {
      throw new FolderNotFoundException;
    }

    return $folder->toArray();
  }

  public function findBySlug ($userId, $slug) {
    return $this->folder->where('user_id', $userId)->where('slug', $slug)->first()->toArray();
  }

  public function findByName ($userId, $name) {
    return $this->folder->where('user_id', $userId)->where('name', $name)->first()->toArray();
  }

  public function addBookmark ($userId, $folderSlug, $bookmarkId) {
    $folder = $this->folder->where('user_id', $userId)->where('slug', $folderSlug)->first();

    $folder->save($bookmarkId);
  }
  
  public function create ($folderData) {
    $slug = $this->makeSlug($folderData['name'], $folderData['user_id']);

    $folder = $this->folder->create([
      'name'    => $folderData['name'],
      'slug'    => $slug,
      'user_id' => $folderData['user_id']
    ]);

    return $folder->id;
  }

  public function update ($folderData) {
    $folder = $this->folder->find($folderData['id']);

    if (!$folder) {
      throw new FolderNotFoundException;
    }

    $slug = $this->makeSlug($folderData['name'], $folderData['user_id']);

    $folder->name = $folderData['name'];
    $folder->slug = $slug;

    $folder->save();
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
    return $this->folder->where('user_id', $userId)->where('slug', $folderSlug)->first()->bookmarks->toArray();
  }

  public function getTotalBookmarks($userId, $folderSlug) {
    return $this->folder->where('user_id', $userId)->where('slug', $folderSlug)->first()->bookmarks->count();
  }
}