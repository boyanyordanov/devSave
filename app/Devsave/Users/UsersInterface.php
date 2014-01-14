<?php namespace Devsave\Users;

interface UsersInterface {
  
  /**
   * Fins all users
   * @return Array
   */
  public function findAll ();
  /**
   * Find a user by their od
   * @param  Integer $id 
   * @return Array
   * @throws Devsave\Exceptions\UserNotFoundException
   */
  public function findById ($id);

  /**
   * Find a user by their login
   * @param  String $email 
   * @return Array        
   * @throws Devsave\Exceptions\UserNotFoundException
   */
  public function findByEmail ($email);

  /**
   * Create a user with an array of data
   * Required: email, password
   * @param  Array $userData 
   * @return Array
   */
  public function create ($userData);

  /**
   * Uodates a user with an array of data
   * Required: id
   * Optional: email or password
   * 
   * @param  Array $userData 
   * @return Array
   * @throws Devsave\Exceptions\UserNotFoundException
   */
  public function update ($userData);

  /**
   * Deletes a user
   * @param  Integer $id 
   * @throws Devsave\Exceptions\UserNotFoundException
   */
  public function delete ($id);
} 