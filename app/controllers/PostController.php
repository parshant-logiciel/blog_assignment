<?php

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Transformers\PostTransformer;
use Sorskod\Larasponse\Larasponse;
use Traits\SortingTrait;


class PostController extends \BaseController
{
	use SortingTrait;
	protected $response;

	public function __construct(Larasponse $response)
	{
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
		$postQuery = Post::query();
		$limit = Input::get('limit') ?: 10;
		$user_id = Input::get('user_id');
		$user_name = Input::get('username');
		$title = Input::get('title');
		if($user_name) {
			$postQuery->leftJoin('users', 'posts.user_id', '=', 'users.id')
				->whereIn('name', $user_name);
		}
		if(Input::get('userFavorite')) {
			$postQuery->where('user_id', Auth::id())->where('is_favorite', '=', TRUE);
		}
		if($title) {
			$postQuery->where('title', 'LIKE', "%$title%");
		}

		if(Input::get('favorite')) {
			$postQuery->where('is_favorite', TRUE);
		}
		if($user_id) {
			$postQuery->whereIn('user_id', $user_id);
		}
		$ordered = $this->scopeOrder($postQuery);
		$posts = $ordered->paginate($limit);
		return Response::json($this->response->paginatedCollection($posts, new PostTransformer), 200);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make(Input::all(), Post::getRules());
		if($validator->fails()) {
			return Response::json($this->response->item($validator->errors()), 412);
		}
		Input::merge(['user_id' => Auth::id()]);
		$data = Post::create(Input::all());
		$transformed = $this->response->item($data, new PostTransformer);
		$message = [
			"Message" => 'Post Created Succesfully',
			"data" => $transformed
		];
		return Response::json($message, 200);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(Auth::check()) {
			$posts = Post::find($id);
			if($posts) {
				return Response::json($this->response->item($posts, new PostTransformer), 200);
			}
			return Response::json(['message' => 'Please Enter a Valid Id'], 404);
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$post = Post::where('id', $id)->get()->first();
		if($post->user_id != Auth::id()) {
			return Response::json(['message' => 'You are not Authorized user'], 403);
		}
		if($post) {
			$data = Input::all();
			$validator = Validator::make($data, Post::getRules($id));
			if ($validator->fails()) {
				return Response::json($this->response->item($validator->errors()), 412);
			}
			$post->user_id = Auth::id();
			$post->title = Input::get('title') ?: $post->title;
			$post->description = Input::get('description') ?: $post->description;
			$result = $post->save();
			if($result) {
				return Response::json($this->response->item($post, new PostTransformer), 200);
			}
		}
		return Response::json(['message' => 'Please enter a Valid id'], 404);
	}
	/**
	 *  To add a favorite Post
	 */
	
	public function addFavorite()
	{
		try {
		
			if(Input::get('is_favorite') == 1) {
				$post = Post::findOrFail(Input::get('post_id'));
				$post->is_favorite = Input::get('is_favorite');
				$post->marked_by = Auth::id();
				$post->save();
				return Response::json(['message' => 'Favorite Marked Successfully'], 200);
			}
			$post = Post::findOrFail(Input::get('post_id'));
			$post->is_favorite = Input::get('is_favorite');
			$post->marked_by = Null;
			$post->save();
			return Response::json(['message' => 'Favorite UnMarked Successfully'], 200);
		} catch (Exception $e) {
			return Response::json(['Message' => $e->getMessage()]);
		}
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$post = Post::find($id);
		if($post) {
			if($post->user_id == Auth::id()) {
				$post->delete();
				$post->comments()->delete();
				return Response::json(['message' => 'Post Deleted Succesfully'], 200);
			}
			return Response::json(['message' => 'UnAuthorized User'], 403);
		}
		return Response::json(['message' => 'Please Enter a Valid Id'], 404);
	}
}

