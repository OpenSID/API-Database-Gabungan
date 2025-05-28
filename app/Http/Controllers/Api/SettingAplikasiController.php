<?php

namespace App\Http\Controllers\Api;

use App\Http\Repository\SettingAplikasiRepository;
use App\Http\Transformers\SettingAplikasiTransformer;

class SettingAplikasiController extends Controller
{
    public function __construct(protected SettingAplikasiRepository $aplikasi)
    {
    }

    public function index()
    {
        return $this->fractal($this->aplikasi->index(), new SettingAplikasiTransformer(), 'aplikasi')->respond();
    }
}
