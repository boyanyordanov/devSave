<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/{userId}', function($userId) {
  $user = \User::find($userId);

  $folders = [];

  foreach ($user->folders as $folder) {
    $folders[] = [
      'name' => $folder->name,
      'slug' => $folder->slug,
      'bookmarks' => array_pluck($folder->bookmarks->toArray(), 'url')
    ];
  }

  $bookmarks = [];
  foreach ($user->bookmarks as $bookmark) {
    $bookmarks [] = [
      'title' => $bookmark->title,
      'url'   => $bookmark->url,
      'tags'  => implode(array_pluck($bookmark->tags->toArray(), 'name'), ', ')
    ];
  }
  return Response::make([
    'user' => $user->email,
    'folders' => $folders,
    'bookmarks' => $bookmarks
  ]);
});