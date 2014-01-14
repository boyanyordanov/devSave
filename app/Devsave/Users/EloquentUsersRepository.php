<?php namespace Devsave\Users;

use User, Hash;
use Devsave\Exceptions\UserNotFoundException;

class EloquentUsersRepository implements UsersInterface {
  
  protected $user;

  public function __construct (User $user) {
    $this->user = $user;
  }

  /**
   * Fins all users
   * @return Array
   */
  public function findAll () {
    return $this->user->all()->toArray();
  }
  
  /**
   * Find a user by their od
   * @param  Integer $id 
   * @return Array
   * @throws Devsave\Exceptions\UserNotFoundException
   */
  public function findById ($id) {
    $user = $this->user->find($id);

    if (!$user) {
      throw new UserNotFoundException;
    }

    return $user->toArray();
  }

  /**
   * Find a user by their login
   * @param  String $email 
   * @return Array        
   * @throws Devsave\Exceptions\UserNotFoundException
   */
  public function findByEmail ($email) {
    $user = $this->user->whereEmail($email)->first();

    if (!$user) {
      throw new UserNotFoundException;
    }

    return $user->toArray();
  }

  /**
   * Create a user with an array of data
   * Required: email, password
   * @param  Array $userData 
   * @return Array
   */
  public function create ($userData) {
    $newUser = $this->user->create([
      'email'    => $userData['email'],
      'password' => Hash::make($userData['password'])
    ]);

    return $newUser->toArray();
  }

  /**
   * Uodates a user with an array of data
   * Required: id
   * Optional: email or password
   * 
   * @param  Array $userData 
   * @return Array
   * @throws Devsave\Exceptions\UserNotFoundException
   */
  public function update ($userData) {
    if (!array_key_exists('id', $userData)) {
      throw new UserNotFoundException;
    }

    $user = $this->user->find($userData['id']);

    if (!$user) {
      throw new UserNotFoundException;
    }

    if (array_key_exists('email', $userData)) {
      $user->email = $userData['email'];
    }

    if (array_key_exists('password', $userData)) {
      $user->password = Hash::make($userData['password']);
    }

    $user->save();

    return $user->toArray();
  }

  /**
   * Deletes a user
   * @param  Integer $id 
   * @throws Devsave\Exceptions\UserNotFoundException
   */
  public function delete ($id) {
    $user = $this->user->find($id);

    if (!$user) {
      throw new UserNotFoundException;
    }

    $user->delete();
  }
}