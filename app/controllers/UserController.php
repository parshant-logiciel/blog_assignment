<?php

use Illuminate\Support\Facades\Validator;
use LucaDegasperi\OAuth2Server\Authorizer;
use Illuminate\Support\Facades\Auth;
use Blog\AuthenticationService;



class UserController extends \BaseController {
	protected $authorizer;
	protected $authService;
	public function __construct(Authorizer $authorizer, AuthenticationService $authenticationService)
	{
		$this->authorizer = $authorizer;
		$this->authService = $authenticationService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function signup()
	{
		$data = Input::only('first_name','last_name','email','password');
		$validator = Validator::make($data, User::getRules());
		if($validator->fails()){
			return Response::json($validator->errors(),412);
		}
		$name = Input::get('first_name').' '. Input::get('last_name');
		$email = Input::get('email');
		$password = Input::get('password');
		User::create(['name' => $name, 'email' => $email, 'password'=> Hash::make($password)]);
		return Response::json(['message'=> 'Account Created Successfully'],200);
	}

	public function login()
	{
		$input = Input::all();
		$user = User::where('email', $input['username'])->first();
		if($user){
		$credentials = [
			'email'    => $input['username'],
			'password' => $input['password'],
		];
		if (Auth::once($credentials)) {
			$token = $this->authorizer->issueAccessToken();
			$token = $this->authService->verify($token);
			$message = [
				'message' => 'Welcome to the Blog',
				'Token' => $token	
			];
			return Response::json($message, 200);
			}
			return Response::json(['message' => 'wrong Credentials'], 403);	
		}
		return Response::json(['message' => 'User doesn\'t  exists'], 404);
	}
	public function logout()
	{
		Auth::logout();
		$response = ['message' => 'You have been successfully logged out!'];
		return Response::json($response, 200);
	}

	public function active(){
		$user = User::where('id', Input::get('user_id'))->first();
		if($user){
			$input = Input::all();
			
		$validator = Validator::make($input, ['is_active' => 'boolean']);
		if($validator->fails())
		{
			return Response::json($validator->errors(), 412);
		}
		if($input['is_active'] == 1)
		{
			$user->is_active = TRUE;
			$user->save();
			
			return Response::json(['message' => 'User Activated Successfully'], 200);
		}
			$user->is_active = FALSE;
			$user->save();
		return Response::json(['message' => 'User Deactiveted Successfully'], 200);
		}
		return	Response::json(['message' => 'Please Enter a Valid Id'], 404);
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
