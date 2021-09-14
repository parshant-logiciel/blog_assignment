<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Profile extends Eloquent implements UserInterface, RemindableInterface
{
    use UserTrait, RemindableTrait;

    protected $table = 'userProfile';

    public $timestamps = true;

    protected $fillable = ['photo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function getRules($id = null)
    {
        $Rules = [
            'profile' =>'required|mimes:jpg,bmp,png,'
            
        ];
        return $Rules;
    }
    public static function scopeFindOrCreate($id)
    {
        $obj = static::find($id);
        return $obj ? : new static;
    }
}
