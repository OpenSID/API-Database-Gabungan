<?php

namespace App\Http\Controllers\Api;

use App\Models\GolonganDarah;

class GolonganDarahController extends Controller
{
    public function count()
    {
        return GolonganDarah::count();
    }
}
