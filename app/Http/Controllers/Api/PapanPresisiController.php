<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\PapanPresisiRepository;
use App\Http\Transformers\PapanPresisiTransformer;
use Illuminate\Support\Facades\Schema;

class PapanPresisiController extends Controller
{
    public function __construct(protected PapanPresisiRepository $papan)
    {
        // pastikan tabel data_presisi_papan sudah ada
        if(!Schema::connection('openkab')->hasTable('data_presisi_papan')) {
            throw new \Exception('Tabel data_presisi_papan belum ada');
        }
    }

    public function __invoke()
    {
        return $this->fractal($this->papan->index(), new PapanPresisiTransformer, 'papan')->respond();
    }
}
