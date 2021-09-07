<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['post', 'reply'];
    public function transform($comments)
    {
     return [
        'id' => $comments->id,
        'user_id' => $comments->user_id,
        'post_id' => $comments->post_id,
        'parent_id' => $comments->parent_id,
        'comments' => $comments->comment,
        'created_at' => $comments->created_at->format('Y-m-d') . " at " . $comments->created_at->format('h:m:s'),
        'updated_at' => $comments->updated_at->format('Y-m-d') . " at " . $comments->updated_at->format('h:m:s')
    ];
    }

    public function includePost($comments)
    {
        $posts = $comments->post;
        if($posts)
        {
            return $this->item($posts, new PostTransformer);
        }
    }
    public function includeReply($comments)
    {
        $reply = $comments->reply;
        if($reply)
        {
            return $this->item($reply, new CommentTransformer);
        }
    }
 
}
