<?php
namespace Traits;
use Post;

trait SortingTrait{

    public static function scopeOrder()
    {
        $sortBy = \Input::get('sort_by') ? : 'id';
        $sortOrder = \Input::get('sort_order') ? : 'desc';
        return Post::orderBy($sortBy, $sortOrder);
        
    }
}