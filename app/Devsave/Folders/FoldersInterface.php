<?php namespace Devsave\Folders;

interface FoldersInterface {
  
  public function findByUser ($userId);
  
  public function findById ($id);

  public function findBySlug ($userId, $slug);

  public function findByName ($userId, $name);

  public function addBookmark ($userId, $folderSlug, $bookmarkId);
  
  public function create ($folderData);

  public function update ($folderData);

  public function delete ($id);
}