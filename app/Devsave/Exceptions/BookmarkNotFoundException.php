<?php namespace Devsave\Exceptions;

use Exception;

class BookmarkNotFoundException extends Exception {
  protected $message = 'The requested bookmark was not found.';
}