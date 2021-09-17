<?php
namespace App\Transformers;

use Illuminate\Http\Response;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    protected $availableIncludes = [ 'profile','departments' ];
    public function transform($user) {
        return [
            'id'      => $user->id,
            'User Name'  => $user->name,
            'Email' => $user->email,
            'Active' => $user->is_active,
            'created_at' => $user->created_at->format('Y-m-d') . " at " . $user->created_at->format('h:m:s')
        ];
    }
    public function includeDepartments($user) {
        $departments =  $user->depart;
        if ($departments) {
            return $this->collection($departments, new DepartmentTransformer);
        }
    }
    public function includeProfile($user) {
        $photo = $user->profile;
        if($photo) {
            return $this->item($photo,function($photo) {
                return [
                    'Photo-Url' => \URL::asset($photo->photo),
                    'created_at' => $photo->created_at->format('Y-m-d') . " at " . $photo->created_at->format('h:m:s'),
                    'updated_at' => $photo->updated_at->format('Y-m-d') . " at " . $photo->updated_at->format('h:m:s'),
                ];
            });
        }
    }
}