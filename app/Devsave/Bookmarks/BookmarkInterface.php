<?php namespace Devsave\Bookmarks;

interface BookmarkInterface {
  
  public function findByUser ($userId);

  public function findById ($id);

  public function findByTag ($userId, $tagSlug);

  public function findByFolder ($userId, $folderSlug);

  public function create ($booksmarkData);

  public function update ($bookmarkData);
  
  public function delete ($id);
} 