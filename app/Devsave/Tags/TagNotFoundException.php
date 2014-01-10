<?php namespace Devsave\Tags;

use Exception;

class TagNotFoundException extends Exception {
  protected $message = 'The requested tag was not found';
}