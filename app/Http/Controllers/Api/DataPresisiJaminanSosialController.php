<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\DataPresisiJaminanSosialRepository;
use App\Http\Transformers\DataPresisiJaminanSosialTransformer;

class DataPresisiJaminanSosialController extends Controller
{
    public function __construct(protected DataPresisiJaminanSosialRepository $jaminanSosial)
    {
    }

    public function index()
    {
        return $this->fractal($this->jaminanSosial->index(), new DataPresisiJaminanSosialTransformer(), 'data_presisi_jaminan_sosial')->respond();
    }
}
