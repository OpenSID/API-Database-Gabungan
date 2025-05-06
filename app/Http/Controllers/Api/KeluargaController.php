<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\KeluargaRepository;
use App\Http\Transformers\RincianKeluargaTransformer;
use App\Models\Keluarga;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class KeluargaController extends Controller
{
    public function __construct(protected KeluargaRepository $keluarga)
    {
    }

    public function __invoke()
    {
    }

    public function keluarga()
    {
        return $this->fractal($this->keluarga->listKeluarga(), new RincianKeluargaTransformer(), 'keluarga')->respond();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return $this->fractal($this->keluarga->rincianKeluarga(), new RincianKeluargaTransformer(), 'rincian keluarga')->respond();
    }

    public function summary()
    {
        return QueryBuilder::for(Keluarga::class)->count();
    }
}
