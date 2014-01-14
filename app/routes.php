<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| The home route and a route to catch all non-api routes 
| and display the home template,
| so that angular can do the routing on the frontend.
|
*/

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@getIndex']);
Route::get('{any?}', 'HomeController@getIndex');

/*
|--------------------------------------------------------------------------
| Session Resource
|--------------------------------------------------------------------------
| 
| Responsible for loggin a user in and out and retrieving information about
| the session.
|
| session - GET     - Returns whether the user is loggin in and information 
|                     about it
| session - POST    - Loggs a user in
| session - DELETE  - Loggs a user out
|
*/

Route::group(['prefix' => 'api/v1', 'before' => ''], function () {

  /*
  |--------------------------------------------------------------------------
  | User Resource
  |--------------------------------------------------------------------------
  | 
  | The users of the application. Will serve for CRUD operations.
  |
  | /api/v1/users   - GET, POST
  | /api/v1/users/{id} - GET, POST, PUT, DELETE 
  |
  */

  Route::resource(
    'users', 
    'Devsave\Api\UsersController', 
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
  );
 
  /*
  |--------------------------------------------------------------------------
  | Bookmarks Resource
  |--------------------------------------------------------------------------
  | 
  | The heart resource of the application. This will be responsible for the 
  | bookmarks each users saves.
  | 
  | - Adding or moving a bookmark to a folder should happen with an update, 
  |   using the PUT request.
  |
  | - Adding or removing a tag from the bookmark should happen again with
  |   an update using the PUT request 
  |
  | /api/v1/users/{userId}/bookmarks                      - GET, POST    
  | /api/v1/users/{userId}/bookmarks/{bookmarkId}         - GET, PUT, DELETE
  |
  | URL parameters: 
  | - folder - filters the bookmarks based on folder
  | - tags   - filters the bookmarks based on tags
  |
  | Examples:
  | /api/v1/users/{userId}/bookmarks?folder=my-folder
  | /api/v1/users/{userId}/bookmarks?tag=my-tag
  |
  */

  Route::resource(
    'users/{userId}/bookmarks', 
    'Devsave\Api\BookmarksController', 
    ['only' => ['index', 'show', 'store', 'update', 'destroy']]
  );

  /*
  |--------------------------------------------------------------------------
  | Folder Resource
  |--------------------------------------------------------------------------
  | 
  | The folder resource. Responsible for retrieving, creating, updating and deleting  
  | folders for a user.
  |
  | /api/v1/users/{userId}/folders        - GET, POST
  | /api/v1/users/{userId}/folders/{slug} - POST, PUT, DELETE 
  | /api/v1/users/{userId}/folders/{slug} - GET - returns information for the folder 
  |                                               and all assoaciated bookmarks 
  |
  */

  /*
  |--------------------------------------------------------------------------
  | Tag Resource
  |--------------------------------------------------------------------------
  | 
  | The tag resource. Responsible for retrieving, creating, updating and deleting  
  | tags for a user.
  |
  | /api/v1/users/{userId}/tags        - GET, POST
  | /api/v1/users/{userId}/tags/{slug} - POST, PUT, DELETE 
  | /api/v1/users/{userId}/tags/{slug} - GET - returns information for the tag 
  |                                               and all assoaciated bookmarks
  |
  */

});