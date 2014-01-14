<?php namespace Devsave\ServiceProviders;

use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider {
  public function register () {
    $app = $this->app;
    
    $app->bind('Devsave\Tags\TagsInterface', 'Devsave\Tags\EloquentTagsRepository');
    
    $app->bind('Devsave\Folders\FoldersInterface', 'Devsave\Folders\EloquentFoldersRepository');

    $app->bind('Devsave\Bookmarks\BookmarkInterface', 'Devsave\Bookmarks\EloquentBookmarkRepository');

    $app->bind('Devsave\Users\UsersInterface', 'Devsave\Users\EloquentUsersRepository');
  }
}