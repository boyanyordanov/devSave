<?php namespace Devsave\Folders;

interface FoldersInterface {
  
  /**
   * Get all folders for a user
   * @param  Integer $userId
   * @return Array
   * @throws Devsave\Exception\UserNotFoundException
   */  
  public function findByUser ($userId);
  
  /**
   * Get a folder by its id  
   * @param  Integer $id
   * @return Array     
   * @throws Devsave\Exceptions\FolderNotFoundException
   */  
  public function findById ($id);

  /**
   * Get a folder by its slug
   * @param  Integer $userId 
   * @param  String $slug   
   * @return Array
   * @throws Devsave\Exceptions\UserNotFoundException
   * @throws Devsave\Exceptions\FolderNotFoundException
   */
  public function findBySlug ($userId, $slug);

  /**
   * Get a folder by its name
   * @param  Integer $userId
   * @param  String $name
   * @return Array
   * @throws Devsave\Exceptions\UserNotFoundException
   * @throws Devsave\Exceptions\FolderNotFoundException
   */
  public function findByName ($userId, $name);

  /**
   * Add a bookmark to a folder providing
   * @param Integer $userId 
   * @param String $folderSlug
   * @param Integer $bookmarkId 
   * @throws Devsave\Exceptions\UserNotFoundException
   * @throws Devsave\Exceptions\FolderNotFoundException
   * @throws Devsave\Exceptions\BookmarkNotFoundException
   */
  public function addBookmark ($userId, $folderSlug, $bookmarkId);

  /**
   * Get the bookmarks associated with the folder with the provided slug
   * @param  Integer $userId
   * @param  String $slug   
   * @return Array         
   * @throws Devsave\Exceptions\UserNotFoundException
   * @throws Devsave\Exceptions\FolderNotFoundException
   */
  public function getBookmarks ($userId, $folderSlug);

  /**
   * Get the number of bookmarks associated with this folder
   * @param  Integer $userId
   * @param  String $slug   
   * @return Integer
   * @throws Devsave\Exceptions\UserNotFoundException
   * @throws Devsave\Exceptions\FolderNotFoundException
   */
  public function getTotalBookmarks ($userId, $folderSlug);
  
  /**
   * Creates a folder by an array of data:
   * Required: user_id, name
   * 
   * @param  Array $folderData
   * @return Array
   * @throws Devsave\Exceptions\UserNotFoundException
   * 
   */
  public function create ($folderData);

  /**
   * Updates a folder by an array of data:
   * Required: user_id, folder_id, name
   * 
   * @param  Array $tagData
   * @return Array
   * @throws Devsave\Exceptions\UserNotFoundException
   * @throws Devsave\Exceptions\TagNotFoundException
   * 
   */
  public function update ($folderData);

  /**
   * Deletes a folder with provided id
   * @param  Integer $id
   * @throws Devsave\Exceptions\FolderNotFoundException
   */
  public function delete ($id);
}