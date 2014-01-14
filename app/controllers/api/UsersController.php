<?php namespace Devsave\Api;

use Devsave\Users\UsersInterface;
use Devsave\Exceptions\UserNotFoundException;

use Response, Input;

class UsersController extends \BaseController {

	protected $users;

	public function __construct (UsersInterface $users) 
	{
		$this->users = $users;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = $this->users->findAll();

		return Response::make([
			'code' => 200,
			'data' => $users
		], 200);	
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if (!Input::has('email') || !Input::get('password')) {
			return Response::make([
				'code' => 400,
				'data' => [
					'message' => 'Not enough data. Email and password are required.'
				]
			], 400);
		}

		$data = [
			'email' 	 => Input::get('email'),
			'password' => Input::get('password')
		];

		$user = $this->users->create($data);

		return Response::make([
			'code' => 200,
			'data' => $user
		], 200);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		try {
			$user = $this->users->findById($id);
		} catch (UserNotFoundException $e) {
			return Response::make([
				'code' => 404,
				'data' => [
					'message' => 'User with id ' . $id . ' not found.'
				]
			], 404);
		}

		return Response::make([
			'code' => 200,
			'data' => $user
		], 200);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$data = [];

		$data['id'] = $id;

		if (Input::has('email')) {
			$data['email'] = Input::get('email');
		}

		if (Input::has('password')) {
			$data['password'] = Input::get('password');
		}

		try {
			$user = $this->users->update($data);
		} catch (UserNotFoundException $e) {
			return Response::make([
				'code' => 404,
				'data' => [
					'message' => 'User with id ' . $id . ' not found.'
				]
			], 404);
		}

		return Response::make([
			'code' => 200,
			'data' => $user
		], 200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		try {
			$this->users->delete($id);
		} catch (UserNotFoundException $e) {
			return Response::make([
				'code' => 404,
				'data' => [
					'message' => 'User with id ' . $id . ' not found.'
				]
			], 404);
		}
	}

}