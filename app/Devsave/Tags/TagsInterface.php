<?php namespace Devsave\Tags;

interface TagsInterface {
  
  public function findByUser ($userId);
  
  public function findById ($id);

  public function findBySlug ($userId, $slug);

  public function findByName ($userId, $name);

  public function create ($tagData);

  public function update ($tagData);

  public function delete ($id);

  public function getBookmarks ($userId, $slug);

  public function getTotalBookmarks ($userId, $slug);
}