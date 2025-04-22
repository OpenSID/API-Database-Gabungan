<?php

namespace App\Http\Repository;

use App\Models\SettingModul;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SettingModulRepository
{
    public function index()
    {
        return QueryBuilder::for(SettingModul::query())
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('config_id'),
                AllowedFilter::exact('slug'),
                AllowedFilter::callback('kode_kecamatan', function ($query, $value) {
                    $query->whereHas('config', function ($query) use ($value) {
                        $query->where('kode_kecamatan', $value);
                    });
                }),
                AllowedFilter::callback('kode_desa', function ($query, $value) {
                    $query->whereHas('config', function ($query) use ($value) {
                        $query->where('kode_desa', $value);
                    });
                }),
            ])->jsonPaginate();
    }
}
