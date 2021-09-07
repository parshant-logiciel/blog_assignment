<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Comment extends Eloquent implements UserInterface, RemindableInterface
{
  use UserTrait, RemindableTrait;

  public $table = 'comments';

  public $primaryKey = 'id';

  public $timestamps = true;

  protected $fillable = ['comments', 'user_id','parent_id'];

  public function post(){
    return $this->belongsTo(Post::class);
  }
  public function user()
  {
    return $this->belongsTo(User::class);
  }
  
  public function parent(){
    return $this->belongsTo(Comment::class,'parent_id','id');
  }
  public function reply()
  {
    return $this->hasMany(Comment::class, 'parent_id', 'id');
  }
  

}
