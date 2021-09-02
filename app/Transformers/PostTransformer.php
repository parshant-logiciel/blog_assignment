<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
  protected $availableIncludes = [
    'comments',
    'user',
    ];
  public function transform($posts)
  { 
    return [
      'id'      => $posts->id,
      'user_id' => $posts->user_id,
      'Post-Title'  => $posts->title,
      'Post-Description' => $posts->description,
      'created_at' => $posts->created_at->format('Y-m-d') . " at " . $posts->created_at->format('h:m:s'),
      'updated_at' => $posts->updated_at->format('Y-m-d') . " at " . $posts->updated_at->format('h:m:s')
    ];
  }
  public function includeComments($posts)
  {
     $comments =  $posts->comments;
     if($comments){
       return $this->collection($comments, new CommentTransformer);
     }
  }
  public function includeUser($posts){
    $user = $posts->user;
    if($user)
    {
      return $this->item($user, new UserTransformer);
    }
  }
}
