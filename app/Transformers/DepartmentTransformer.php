<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class DepartmentTransformer extends TransformerAbstract
{
    protected $availableIncludes =[
        'user'
    ];
    public function transform($department)
    {

        return [
            'id'      => $department->id,
            'Department Name'  => $department->name,
            'created_at' => $department->created_at->format('Y-m-d') . " at " . $department->created_at->format('h:m:s'),
            'updated_at' => $department->updated_at->format('Y-m-d') . " at " . $department->updated_at->format('h:m:s')
        ];
    }
    public function includeUser($department){
        $data = $department->users;
        if($data)
        {
            return $this->collection($data, new UserTransformer);
        }

    }
}
