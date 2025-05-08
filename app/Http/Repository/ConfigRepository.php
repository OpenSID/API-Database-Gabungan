<?php

namespace App\Http\Repository;

use App\Models\Config;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ConfigRepository
{
    public function desa()
    {
        return QueryBuilder::for(Config::class)
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::exact('id'),
            ])
            ->get();
    }

    public function kecamatan()
    {
        return QueryBuilder::for(Config::class)->groupBy('kode_kecamatan')->get();
    }
}
