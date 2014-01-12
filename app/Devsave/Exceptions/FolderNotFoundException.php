<?php namespace Devsave\Folders;

use Exception;

class FolderNotFoundException extends Exception {
  protected $message = 'The requested folder was not found';
}