<?php

namespace App\Http\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'foto' => $user->foto,
            'role' => $user->roles?->first()->name ?? null,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ];
    }

}
