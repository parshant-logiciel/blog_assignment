<?php

namespace services;

use Repositories\ProfileRepo;
use Illuminate\Support\Facades\Auth;

class ImageUploadService
{
    public function __construct(ProfileRepo $repo)
    {
        $this->repo = $repo;
    }
    public function image($file)
    {
        $name = 'profile_pic';
        $extension = $file->getClientOriginalExtension();
        $fileName = $name . Auth::id() . '.' . $extension;
        $destinationPath = public_path('profile_photo/');
        $file->move($destinationPath, $fileName);
        $url = '/profile_photo/' . $fileName;
        $image = $this->repo->save($url);
        return $image;
    }
}
