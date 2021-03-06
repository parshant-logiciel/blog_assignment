<?php

use App\Transformers\DepartmentTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Validator;
use LucaDegasperi\OAuth2Server\Authorizer;
use Illuminate\Support\Facades\Auth;
use Repositories\ProfileRepo;
use services\ImageUploadService;
use services\AuthenticationService;
use Sorskod\Larasponse\Larasponse;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Response;

class UserController extends \BaseController 
{
	protected $authorizer;
	protected $authService;
	public function __construct(
		Authorizer $authorizer,
		AuthenticationService $authenticationService,
		ImageUploadService $service,
		ProfileRepo $repo,
		Filesystem $filesystem,
		Larasponse $response 
	) {
		$this->authorizer = $authorizer;
		$this->authService = $authenticationService;
		$this->service = $service;
		$this->repo = $repo;
		$this->fileSystem = $filesystem;
		$this->response = $response;
		if (Input::get('includes')) {
			$this->response->parseIncludes(Input::get('includes'));
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$data = User::all();
		return Response::json($this->response->collection($data, new UserTransformer));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function signup()
	{
		$data = Input::only('first_name', 'last_name', 'email', 'password');
		$validator = Validator::make($data, User::getRules());
		if($validator->fails()) {
			return Response::json($validator->errors(), 412);
		}
		$name = Input::get('first_name') . ' ' . Input::get('last_name');
		$email = Input::get('email');
		$password = Input::get('password');
		User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password)]);
		return Response::json(['message' => 'Account Created Successfully'], 200);
	}

	public function login()
	{
		$input = Input::all();
		$user = User::where('email', $input['username'])->first();
		if($user) {
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

	public function active()
	{
		$user = User::where('id', Input::get('user_id'))->first();
		if($user) {
			$input = Input::all();
			$validator = Validator::make($input, ['is_active' => 'boolean']);
			if($validator->fails()) {
				return Response::json($validator->errors(), 412);
			}
			if($input['is_active'] == 1) {
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
	 * @return Response
	 */
	public function upload_profile()
	{
		$input = Input::all();
		$validator = Validator::make($input, Profile::getRules());
		if($validator->fails()) {
			return Response::json($validator->errors(), 412);
		}
		if(Input::hasFile('profile')) {
			$file = Input::file('profile');
			$url = $this->service->image($file);
			$message = [
				"Message" => 'Profile Uploaded Succesfully',
				"data" => $url
			];
			return Response::json($message, 200);
		}
	}

	public function profile()
	{
		$data = User::Where('id', Auth::id())->first();
		return Response::json($this->response->item($data, new UserTransformer), 200);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function department()
	{
		$user = User::where('id', Input::get('user_id'))->first();
		$departmentId = Input::get('department_id');
		$user->depart()->sync($departmentId);
		return Response::json('inserted successfully', 200);
	}
	public function departmentIndex()
	{
		$data = Department::all();
		return Response::json($this->response->collection($data, new DepartmentTransformer));
	}

}
