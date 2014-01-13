<?php namespace Devsave\Tags;

use Tag, User, Str;

use Devsave\Exceptions\TagNotFoundException;
use Devsave\Exceptions\UserNotFoundException;

class EloquentTagsRepository implements TagsInterface {
  protected $tag;

  protected $user;

  public function __construct (Tag $tag, User $user) {
    $this->tag = $tag;

    $this->user = $user;
  }

  public function findByUser ($userId) {
    $user = $this->user->find($userId);

    if (!$user) {
      throw new UserNotFoundException;
    }

    return $user->tags->toArray();
  }
  
  public function findById ($id) {
    $tag = $this->tag->find($id);

    if (!$tag) {
      throw new TagNotFoundException;
    }

    return $tag->toArray();
  }

  public function findBySlug ($userId, $slug) {
    $user = $this->user->find($userId);

    if (!$user) {
      throw new UserNotFoundException;
    }

    $tag = $user->tags()->where('slug', $slug)->first();

    if (!$tag) {
      throw new TagNotFoundException;
    }
    
    return $tag->toArray();
  }

  public function findByName ($userId, $name) {
    $user = $this->user->find($userId);

    if (!$user) {
      throw new UserNotFoundException;
    }

    $tag = $user->tags()->where('name', $name)->first();

    if (!$tag) {
      throw new TagNotFoundException;
    }
    
    return $tag->toArray();
  }
  
  public function create ($tagData) {
    if (!$this->user->find($tagData['user_id'])) {
      throw new UserNotFoundException;
    }

    $slug = $this->makeSlug($tagData['name'], $tagData['user_id']);

    $tag = $this->tag->create([
      'name'    => $tagData['name'],
      'slug'    => $slug,
      'user_id' => $tagData['user_id']
    ]);

    return $tag->toArray();
  }

  public function update ($tagData) {
    if (!$this->user->find($tagData['user_id'])) {
      throw new UserNotFoundException;
    }

    $tag = $this->tag->find($tagData['id']);

    if (!$tag) {
      throw new TagNotFoundException;
    }

    $slug = $this->makeSlug($tagData['name'], $tagData['user_id']);

    $tag->name = $tagData['name'];
    $tag->slug = $slug;

    $tag->save();

    return $tag->toArray();
  }

  public function delete ($id) {
    $tag = $this->tag->find($id);

    if (!$tag) {
      throw new TagNotFoundException;
    }

    $tag->delete();
  }

  protected function makeSlug ($name, $userId) {
    $slug = Str::slug($name);

    $tagsWithSlug = $this->tag->where('user_id', $userId)->where('slug', $slug)->count();

    if ($tagsWithSlug > 0) {
      $newNumber = $tagsWithSlug + 1;

      $slug = $slug . '-' . $newNumber;
    }
    
    return $slug;
  }

  public function getBookmarks ($userId, $slug) {
    if (!$this->user->find($userId)) {
      throw new UserNotFoundException;
    }

    $tag = $this->tag->where('user_id', $userId)->where('slug', $slug)->first();

    if (!$tag) {
      throw new TagNotFoundException;
    }

    return $tag->bookmarks->toArray();
  }

  public function getTotalBookmarks ($userId, $slug) {
    if (!$this->user->find($userId)) {
      throw new UserNotFoundException;
    }

    $tag = $this->tag->where('user_id', $userId)->where('slug', $slug)->first();

    if (!$tag) {
      throw new TagNotFoundException;
    }

    return $tag->bookmarks->count();
  }
}