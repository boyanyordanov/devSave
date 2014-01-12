<?php namespace Devsave\Bookmarks;

interface BookmarkInterface {
  
  /**
   * Get all bookmarks that a user has saved
   * 
   * @param  Inteer $userId
   * @return Array
   * @throws Devsave\Exceptions\UserNotFoundException if a user with the provided id does not exist
   */
  public function findByUser ($userId);

  /**
   * Get a specific bookmark
   * 
   * @param  Integer $id
   * @return Array
   * @throws Devsave\Exceptions\BookmarkNotFoundException if a bookmark with the provided id does not exist 
   */
  public function findById ($id);

  /**
   * Get all bookmarks for a tag.
   * Since we want to isolate all users from each other,
   * a user id must be provided along with a slug for a tag.
   * 
   * @param  Integer $userId
   * @param  String $tagSlug 
   * @return Array
   * @throws Devsave\Exceptions\UserNotFoundException if a user with the provided id does not exist
   * @throws Devsave\Exceptions\TagNotFoundException if a tag with the provided slug does not exist
   */
  public function findByTag ($userId, $tagSlug);

  /**
   * Get all bookmarks for a folder.
   * Since we want to isolate all users from each other,
   * a user id must be provided along with a slug for a folder.
   * 
   * @param  Integer $userId
   * @param  String $folderSlug 
   * @return Array
   * @throws Devsave\Exceptions\UserNotFoundException if a user with the provided id does not exist
   * @throws Devsave\Exceptions\FolderNotFoundException if a folder with the provided slug does not exist
   */
  public function findByFolder ($userId, $folderSlug);

  /**
   * Creates a bookmark for a user from a associative array with data:
   * Required: user_id, url
   * Optional: title, notes, folder_id and tags (an array of tag slugs)
   * 
   * @param  Array $booksmarkData
   * @return Array The created bookmark
   */
  public function create ($booksmarkData);

  /**
   * Updates a bookmark for a user from a associative array with data:
   * Required: id
   * Optional: url, title, notes, folder_id and tags (an array of tag slugs)
   * 
   * @param  Array $booksmarkData
   * @return Array The created bookmark
   */
  public function update ($bookmarkData);
  
  /**
   * Deletes the bookmark with the provided id
   * @param  Integer $id
   */
  public function delete ($id);

  /**
   * Get the total number of bookmarks for a prticular user
   * @param  Integer $userId
   * @return Integer
   * @throws Devsave\Bhawk\Exceptions\UserNotFoundException         
   */ 
  public function getTotal($userId);

  /**
   * Get the total number of bookmarks in a given folder
   * @param  Integer $userId     
   * @param  Integer $folderSlug
   * @return Integer
   * @throws Devsave\Bhawk\Exceptions\UserNotFoundException
   * @throws Devsave\Bhawk\Exceptions\FolderNotFoundException         
   */
  public function getTotalForFolder($userId, $folderSlug);

  /**
   * Get the total number of bookmarks in a given tag
   * @param  Integer $userId     
   * @param  Integer $tagSlug
   * @return Integer
   * @throws Devsave\Bhawk\Exceptions\UserNotFoundException
   * @throws Devsave\Bhawk\Exceptions\TagNotFoundException         
   */
  public function getTotalForTag($userId, $tagSlug);
} 