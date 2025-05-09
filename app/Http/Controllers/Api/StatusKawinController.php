<?php

namespace App\Http\Controllers\Api;

use App\Models\StatusKawin;

class StatusKawinController extends Controller
{
    public function count()
    {
        return StatusKawin::count();
    }
}
