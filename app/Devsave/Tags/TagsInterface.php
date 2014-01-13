<?php namespace Devsave\Tags;

interface TagsInterface {
  
  /**
   * Get all tags for a user
   * @param  Integer $userId
   * @return Array
   * @throws Devsave\Exception\UserNotFoundException
   */
  public function findByUser ($userId);
  
  /**
   * Get a tag by its id  
   * @param  Integer $id
   * @return Array     
   * @throws Devsave\Exceptions\TagNotFoundException
   */
  public function findById ($id);

  /**
   * Get a tag by its slug
   * @param  Integer $userId 
   * @param  String $slug   
   * @return Array
   * @throws Devsave\Exceptions\UserNotFoundException
   * @throws Devsave\Exceptions\TagNotFoundException
   */
  public function findBySlug ($userId, $slug);

  /**
   * Get a tag by its name
   * @param  Integer $userId
   * @param  String $name
   * @return Array
   * @throws Devsave\Exceptions\UserNotFoundException
   * @throws Devsave\Exceptions\TagNotFoundException
   */
  public function findByName ($userId, $name);

  /**
   * Creates a tag by an array of data:
   * Required: user_id, name
   * 
   * @param  Array $tagData
   * @return Array
   * @throws Devsave\Exceptions\UserNotFoundException
   * 
   */
  public function create ($tagData);

  /**
   * Updates a tag by an array of data:
   * Required: user_id, tag_id, name
   * 
   * @param  Array $tagData
   * @return Array
   * @throws Devsave\Exceptions\UserNotFoundException
   * @throws Devsave\Exceptions\TagNotFoundException
   * 
   */
  public function update ($tagData);

  /**
   * Deletes a tag with provided id
   * @param  Integer $id
   * @throws Devsave\Exceptions\TagNotFoundException
   */
  public function delete ($id);

  /**
   * Get the bookmarks associated with the tag with the provided slug
   * @param  Integer $userId
   * @param  String $slug   
   * @return Array         
   * @throws Devsave\Exceptions\UserNotFoundException
   * @throws Devsave\Exceptions\TagNotFoundException
   */
  public function getBookmarks ($userId, $slug);

  /**
   * Get the number of bookmarks associated with this tag
   * @param  Integer $userId
   * @param  String $slug   
   * @return Integer
   * @throws Devsave\Exceptions\UserNotFoundException
   * @throws Devsave\Exceptions\TagNotFoundException
   */
  public function getTotalBookmarks ($userId, $slug);
}