<?php

namespace App\Http\Transformers;

use App\Models\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    public function transform(Role $role)
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
            'guard_name' => $role->guard_name,
            'user_count' => $role->users->count(),
            'created_at' => $role->created_at,
            'updated_at' => $role->updated_at
        ];
    }

}
