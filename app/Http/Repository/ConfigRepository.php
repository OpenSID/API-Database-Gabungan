<?php

namespace App\Http\Repository;

use App\Models\Config;
use Spatie\QueryBuilder\QueryBuilder;

class ConfigRepository
{
    public function desa()
    {
        return QueryBuilder::for(Config::class)->get();
    }

    public function kecamatan()
    {
        return QueryBuilder::for(Config::class)->groupBy('kode_kecamatan')->get();
    }

    public function kabupaten()
    {
        return QueryBuilder::for(Config::class)->select('nama_kabupaten', 'kode_kabupaten')->distinct()->get();
    }
}
