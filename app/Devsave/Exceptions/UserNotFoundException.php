<?php namespace Devsave\Exceptions;

use Exception;

class UserNotFoundException extends Exception {
  protected $message = 'The requested user was not found';
}