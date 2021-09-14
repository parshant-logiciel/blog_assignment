<?php
namespace Repositories;

use Profile;
use Illuminate\Support\Facades\Auth;
class ProfileRepo{

    public function save($url){
        $link = \URL::asset($url);
        $profile = Profile::scopeFindOrCreate(Auth::id());
        $profile->user_id = Auth::id();
        $profile->profile = $url;
        $profile->save();
        return $link;
    }
}