<?php

namespace Traits;

trait SortingTrait
{
    public static function scopeOrder($query) 
    {
        $sortBy = \Input::get('sort_by') ?: 'posts.id';
        $sortOrder = \Input::get('sort_order') ?: 'desc';
        return $query->orderBy($sortBy, $sortOrder);
    }
}