<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\DataPresisiAdatRepository;
use App\Http\Transformers\DataPresisiAdatTransformer;

class DataPresisiAdatController extends Controller
{
    public function __construct(protected DataPresisiAdatRepository $agama)
    {
    }

    public function index()
    {
        return $this->fractal($this->agama->index(), new DataPresisiAdatTransformer(), 'agama')->respond();
    }
}
