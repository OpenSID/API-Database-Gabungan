<?php

namespace App\Http\Repository;

use App\Models\Config;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ConfigRepository
{
    public function desa()
    {
        return QueryBuilder::for(Config::class)
                ->allowedFilters([
                    AllowedFilter::exact('id'),
                    AllowedFilter::callback('kode_kabupaten', function ($query, $value) {
                        $query->where('kode_kabupaten', $value);
                    }),
                    AllowedFilter::callback('kode_kecamatan', function ($query, $value) {
                        $query->where('kode_kecamatan', $value);
                    }),
                    AllowedFilter::callback('kode_desa', function ($query, $value) {
                        $query->where('kode_desa', $value);
                    })
                ])
                ->get();
    }

    public function kecamatan()
    {
        return QueryBuilder::for(Config::class)
                ->selectRaw('max(nama_kecamatan) as nama_kecamatan, max(kode_kecamatan) as kode_kecamatan')
                ->groupBy('kode_kecamatan')
                // ->distinct()
                ->allowedFilters([
                    AllowedFilter::exact('id'),
                    AllowedFilter::callback('kode_kabupaten', function ($query, $value) {
                        $query->where('kode_kabupaten', $value);
                    }),
                    AllowedFilter::callback('kode_kecamatan', function ($query, $value) {
                        $query->where('kode_kecamatan', $value);
                    })
                ])
                ->get();
    }

    public function kabupaten()
    {
        return QueryBuilder::for(Config::class)
                ->select('nama_kabupaten', 'kode_kabupaten')
                ->distinct()
                ->allowedFilters([
                    AllowedFilter::exact('id'),
                    AllowedFilter::callback('kode_kabupaten', function ($query, $value) {
                        $query->where('kode_kabupaten', $value);
                    }),
                ])
                ->get();
    }
}
