<?php namespace Devsave\Api;

use Devsave\Bookmarks\BookmarkInterface;
use Devsave\Users\UsersInterface;

use Devsave\Exceptions\UserNotFoundException;
use Devsave\Exceptions\BookmarkNotFoundException;

use Response, Input;

class BookmarksController extends \BaseController {

	protected $bookmarks;

	protected $users;

	public function __construct (BookmarkInterface $bookmarks, UsersInterface $users) {
		$this->bookmarks = $bookmarks;
		$this->users = $users;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($userId)
	{	
		try {
			if (Input::has('tag')) {
				$bookmarks = $this->bookmarks->findByTag($userId, Input::get('tag'));
				
				$totalBookmarks = $this->bookmarks->getTotalForTag($userId, Input::get('tag'));
			} else if (Input::has('folder')) {
				$bookmarks = $this->bookmarks->findByFolder($userId, Input::get('folder'));
				
				$totalBookmarks = $this->bookmarks->getTotalForFolder($userId, Input::get('folder'));
			} else {
				$bookmarks = $this->bookmarks->findByUser($userId);
				
				$totalBookmarks = $this->bookmarks->getTotal($userId);
			}
		} catch (UserNotFoundException $e) {
			return Response::make([
				"code" => 400,
				"data" => [
					"message" => "Cannot retrieve bookmarks for non existing user with id $userId."
				]
			], 400);
		}


		return Response::make([
			"code" => 200,
			"data" => [
				"total" => $totalBookmarks,
				"items" => $bookmarks
			]
		], 200);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($userId, $id)
	{
		try {
			$user = $this->users->findById($userId);

			$bookmark = $this->bookmarks->findById($id);
		} catch (BookmarkNotFoundException $e) {
			return Response::make([
				"code" => 404,
				"data" => [
					"message" => "Bookmark with id $id not found."
				]
			], 404);
		} catch (UserNotFoundException $e) {
			return Response::make([
				"code" => 400,
				"data" => [
					"message" => "Cannot retrieve bookmark for non existing user with id $userId."
				]
			], 400);
		}

		return Response::make([
			"code" => 200,
			"data" => (object) $bookmark
		], 200);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($userId)
	{	
		if (!Input::has('url')) {
			return Response::make([
				"code" => 400, 
				"data" => [
					"message" => "Not enough data. Url is required."
				]
			], 400);
		}

		$data = [
			'user_id' 	=> $userId,
			'url' 			=> Input::get('url'),
			'title' 		=> Input::get('title'),
			'notes' 		=> Input::get('notes'),
			'folder_id' => Input::get('folder_id'),
		];

		try {
			$bookmark = $this->bookmarks->create($data);
		} catch (UserNotFoundException $e) {
			return Response::make([
				"code" => 400,
				"data" => [
					"message" => "Cannot create bookmark for non existing user."
				]
			], 400);
		}

		return Response::make([
			"code" => 200,
			"data" => $bookmark
		], 200);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($userId, $id)
	{
		if (!Input::has('url')) {
			return Response::make([
				"code" => 400,
				"data" => [
					"message" => "Not enough data. Bookmark url is required."
				] 
			], 400);
		}

		$data = [
			'id'				=> $id,
			'user_id' 	=> $userId,
			'url'				=> Input::get('url'),
			'title'		  => Input::get('title'),
			'notes'			=> Input::get('notes'),
			'folder_id'	=> Input::get('folder_id'),
		];

		try {
			$user = $this->users->findById($userId);

			$bookmark = $this->bookmarks->update($data);
		} catch (BookmarkNotFoundException $e) {
			return Response::make([
				"code" => 404,
				"data" => [
					"message" => "Bookmark with id $id not found."
				]
			], 404);
		} catch (UserNotFoundException $e) {
			return Response::make([
				"code" => 400,
				"data" => [
					"message" => "Cannot update bookmark for non existing user."
				]
			], 400);
		}

		return Response::make([
			"code" => 200,
			"data" => $bookmark
		], 200);	
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($userId, $id)
	{
		try {
			$user = $this->users->findById($userId);

			$this->bookmarks->delete($id);
		} catch (BookmarkNotFoundException $e) {
			return Response::make([
				"code" => 404,
				"data" => [
					"message" => "Bookmark with id $id not found."
				]
			], 404);
		} catch (UserNotFoundException $e) {
			return Response::make([
				"code" => 400,
				"data" => [
					"message" => "Wrong url format. User with id $userId doesn't exist."
				]
			], 400);
		}

		return Response::make([
			"code" => 200,
			"data" => [
				"message" => "Bookmark deleted successfully."
			]
		], 200);
	}

}