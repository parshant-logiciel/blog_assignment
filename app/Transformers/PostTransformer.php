<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
  protected $availableIncludes = ['comments', 'user', 'favoriteBy'];
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
    if($comments) {
      return $this->collection($comments, new CommentTransformer);
    }
  }
  public function includeUser($posts)
  {
    $user = $posts->user;
    if($user) {
      return $this->item($user, new UserTransformer);
    }
  }
  public function includeFavoriteBy($posts)
  {
    $favorite_By = $posts->favoriteByUser;
    if($favorite_By) {
      return $this->item($favorite_By, function ($favorite_By) {
        return [
          'User ID' => $favorite_By->id,
          'Favorited-by' => $favorite_By->name,
        ];
      });
    }
  }
}
