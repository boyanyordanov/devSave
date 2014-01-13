<?php namespace Devsave\Exceptions;

use Exception;

class FolderNotFoundException extends Exception {
  protected $message = 'The requested folder was not found';
}