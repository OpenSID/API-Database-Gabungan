<?php

namespace App\Http\Repository;

use App\Models\Config;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ConfigRepository
{
    public function desa()
    {
        return QueryBuilder::for(Config::class)
            ->allowedFilters([
                AllowedFilter::callback('kode_kabupaten', function ($query, $value) {
                    $query->where('kode_kabupaten', $value);
                }),
                AllowedFilter::callback('kode_kecamatan', function ($query, $value) {
                    $query->where('kode_kecamatan', $value);
                }),
                AllowedFilter::callback('kode_desa', function ($query, $value) {
                    $query->where('kode_desa', $value);
                }),
            ])
            ->get();
    }

    public function kecamatan()
    {
        return QueryBuilder::for(Config::class)->groupBy('kode_kecamatan')->get();
    }
}
