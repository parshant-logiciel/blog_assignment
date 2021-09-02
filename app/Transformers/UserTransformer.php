<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform($user)
    {
        return [
            'id'      => $user->id,
            'User Name'  => $user->name,
            'Email' => $user->email,
            'Active' => $user->is_active
        ];
    }
}