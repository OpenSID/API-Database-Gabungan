<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\PendudukRepository;
use App\Http\Transformers\PendidikanTransformer;
use App\Models\Pendidikan;
use App\Models\PendidikanKK;

class PendidikanController extends Controller
{
    public function __construct(
        protected PendudukRepository $penduduk
    ) {
    }

    public function __invoke()
    {
        return $this->fractal($this->penduduk->listPendudukPendidikan(), new PendidikanTransformer, 'pendidikan')->respond();
    }

    public function countPendidikanKK()
    {
        return PendidikanKK::count();
    }

    public function countPendidikan()
    {
        return Pendidikan::count();
    }
}
