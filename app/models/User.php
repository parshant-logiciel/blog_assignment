<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;


class User extends Eloquent implements UserInterface, RemindableInterface 
{
	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	protected $hidden = array('password', 'remember_token');

	protected $fillable = ['name', 'user_id', 'email', 'password', 'active' ];

	public static function getRules() 
	{
		$Rules = [
			'first_name' => 'required|max:12|min:3',
			'last_name' => 'required|max:10|min:3',
			'email' => 'required|email|unique:users,email'
			];
		return $Rules;
	}
	public function posts()	
	{
		return $this->hasMany(Post::class);
	}
	public function comments() 
	{
		return $this->hasMany(Comment::class);
	}
	public function profile() 
	{
		return $this->hasOne(Profile::class);
	}
	public function depart() 
	{
		return $this->belongsToMany(Department::class);
	}
}
