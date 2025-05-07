<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\KesehatanWebsiteRepository;
use App\Http\Transformers\KesehatanWebsiteTransformer;

class KesehatanWebsiteController extends Controller
{
    public function __construct(protected KesehatanWebsiteRepository $kesehatan)
    {
    }

    public function __invoke()
    {
        $filters = [
            'config_id' => request('config_id'),
            'tahun' => request('tahun'),
            'kode_kecamatan' => request('kode_kecamatan'),
            'desa' => request('desa'),
            'posyandu' => request('posyandu'),
            'kabupaten' => request('kabupaten'),
            'kuartal' => request('kuartal'),
        ];

        return $this->fractal($this->kesehatan->index($filters), new KesehatanWebsiteTransformer, 'kesehatan')->respond();
    }
}
