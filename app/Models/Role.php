<?php

namespace App\Models;

use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    public static array $rules = [
        'name' => 'required|string|max:125',
        'guard_name' => 'required|string|max:125'
    ];
}
