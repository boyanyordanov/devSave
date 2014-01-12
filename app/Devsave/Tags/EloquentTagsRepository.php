<?php namespace Devsave\Tags;

use Tag, Str;

class EloquentTagsRepository implements TagsInterface {
  protected $tag;

  public function __construct (Tag $tag) {
    $this->tag = $tag;
  }

  public function findByUser ($userId) {
    return $this->tag->where('user_id', $userId)->get()->toArray();
  }
  
  public function findById ($id) {
    $tag = $this->tag->find($id);

    if (!$tag) {
      throw new TagNotFoundException;
    }

    return $tag->toArray();
  }

  public function findBySlug ($userId, $slug) {
    return $this->tag->where('user_id', $userId)->where('slug', $slug)->first()->toArray();
  }

  public function findByName ($userId, $name) {
    return $this->tag->where('user_id', $userId)->where('name', $name)->first()->toArray();
  }
  
  public function create ($tagData) {
    $slug = $this->makeSlug($tagData['name'], $tagData['user_id']);

    $tag = $this->tag->create([
      'name'    => $tagData['name'],
      'slug'    => $slug,
      'user_id' => $tagData['user_id']
    ]);

    return $tag->id;
  }

  public function update ($tagData) {
    $tag = $this->tag->find($tagData['id']);

    if (!$tag) {
      throw new TagNotFoundException;
    }

    $slug = $this->makeSlug($tagData['name'], $tagData['user_id']);

    $tag->name = $tagData['name'];
    $tag->slug = $slug;

    $tag->save();
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
    return $this->tag->where('user_id', $userId)->where('slug', $slug)->first()->bookmarks->toArray();
  }

  public function getTotalBookmarks ($userId, $slug) {
    return $this->tag->where('user_id', $userId)->where('slug', $slug)->first()->bookmarks->count();
  }
}