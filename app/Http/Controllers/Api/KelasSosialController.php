<?php

namespace App\Http\Controllers\Api;

use App\Models\KelasSosial;

class KelasSosialController extends Controller
{
    public function count()
    {
        return KelasSosial::count();
    }
}
