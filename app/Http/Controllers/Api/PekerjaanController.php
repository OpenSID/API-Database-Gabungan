<?php

namespace App\Http\Controllers\Api;

use App\Models\Pekerjaan;

class PekerjaanController extends Controller
{
    public function count()
    {
        return Pekerjaan::count();
    }
}
