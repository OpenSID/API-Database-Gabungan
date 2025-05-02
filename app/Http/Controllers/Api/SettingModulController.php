<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\SettingModulRepository;
use App\Http\Transformers\SettingModulTransformer;

class SettingModulController extends Controller
{
    public function __construct(protected SettingModulRepository $modul)
    {
    }

    public function index()
    {
        return $this->fractal($this->modul->index(), new SettingModulTransformer(), 'modul')->respond();
    }
}
