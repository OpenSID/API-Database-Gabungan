<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\ConfigRepository;
use App\Http\Transformers\ConfigKabupatenTransformer;
use App\Http\Transformers\ConfigKecamatanTransformer;
use App\Http\Transformers\ConfigTransformer;

class ConfigController extends Controller
{
    public function __construct(protected ConfigRepository $config)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->fractal($this->config->desa(), new ConfigTransformer(), 'config')->respond();
    }

    public function kecamatan()
    {
        return $this->fractal($this->config->kecamatan(), new ConfigKecamatanTransformer(), 'config')->respond();
    }
    
    public function kabupaten()
    {
        return $this->fractal($this->config->kabupaten(), new ConfigKabupatenTransformer(), 'config')->respond();
    }
}
