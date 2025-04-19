<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\DataPresisiAgamaRepository;
use App\Http\Transformers\DataPresisiAgamaTransformer;

class DataPresisiAgamaController extends Controller
{
    public function __construct(protected DataPresisiAgamaRepository $agama)
    {
    }

    public function index()
    {
        return $this->fractal($this->agama->index(), new DataPresisiAgamaTransformer(), 'agama')->respond();
    }
}
