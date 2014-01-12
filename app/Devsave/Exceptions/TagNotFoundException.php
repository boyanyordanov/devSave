<?php namespace Devsave\Exceptions;

use Exception;

class TagNotFoundException extends Exception {
  protected $message = 'The requested tag was not found';
}