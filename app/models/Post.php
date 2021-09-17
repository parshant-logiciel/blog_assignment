<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Post extends Eloquent implements UserInterface, RemindableInterface
{
  use UserTrait, RemindableTrait;

  protected $table = 'posts';

  public $timestamps = true;

  protected $fillable = ['title', 'description', 'user_id'];

  protected static function getRules($id = null)
  {
    $Rules = [
      'title' => 'required|max:20|unique:posts,title' . ($id ? ",$id" : ''),
      'description' => 'required|max:200',
    ];
    return $Rules;
  }
  public function comments() 
  {
    return $this->hasMany(Comment::class);
  }
  
  public function user() 
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
  public function favoriteByUser() 
  {
    
    return $this->belongsTo(User::class, 'marked_by', 'id');
  }
}


