<?php
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Transformers\CommentTransformer;
use Sorskod\Larasponse\Larasponse;
class CommentController extends \BaseController {

	protected $response;

	public function __construct(Larasponse $response)
	{
		$this->beforeFilter('oauth');
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
		$limit = Input::get('limit') ?: 10;
		$data = Comment::paginate($limit);
		return Response::json($this->response->paginatedCollection($data, new CommentTransformer));
	}

	

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		try{
			$post = Post::findOrFail(Input::get('post_id'));
			$input = Input::all();
			$validator = Validator::make($input, ['post_id' => 'integer', 'comment' => 'min:3|max:200']);
			if ($validator->fails()) {
				return Response::json($this->response->item($validator->errors()), 412);
			}
			$comment = new Comment;
			$comment->comment = Input::get('comment');
			$comment->user_id = Auth::id();
			$comment->post_id = Input::get('post_id');
			if(Input::get('parent_id')) {

				$current = Comment::where('id', '=', Input::get('parent_id'))->first();

				if($current && ($first = $current->parent) && ($first->parent) ) {
					return Response::json(['message' => 'Restriction Reached'], 412);
				}

				$comment->parent_id = Input::get('parent_id');
			}

			$comment->save();
			$transformed = $this->response->item($comment, new CommentTransformer);
			$message = [
				"Message" => 'Commented Succesfully',
				"data" => $transformed
			];
			return Response::json($message, 200);
		} catch(Exception $e){
			return Response::json(['message' => 'Post Not Found'], 404);
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
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
		if ($comment = Comment::find($id)) {
			$comment->comment = Input::get('comment') ?: $comment->comment;
			$result = $comment->save();
			if ($result) {
				return Response::json($this->response->item($comment, new CommentTransformer), 200);
			}
		}
		return Response::json(['message' => 'Please enter a Valid id'], 404);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$comment = Comment::find($id);
		if ($comment) {
			$comment->delete();
			return Response::json(['message' => 'Comment Deleted Succesfully'], 200);
		}
		return Response::json(['message' => 'Please Enter a Valid Id'], 404);
	}


}
