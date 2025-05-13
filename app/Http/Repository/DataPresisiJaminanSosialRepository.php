<?php

namespace App\Http\Repository;

use App\Models\DataPresisiJaminanSosial;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

class DataPresisiJaminanSosialRepository
{
    public function index()
    {
        return QueryBuilder::for(DataPresisiJaminanSosial::tahunAktif()->filterWilayah())
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('rtm_id'),
                AllowedFilter::exact('config_id'),
                AllowedFilter::exact('keluarga_id'),
                AllowedFilter::exact('anggota_id'),
                AllowedFilter::callback('kepala_rtm', function ($query, $value) {
                    $query->kepalaRtm();
                }),
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

                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->whereRelation('penduduk','nama', 'like', "%{$value}%")
                        ->orWhereRelation('penduduk','nik', 'like', "%{$value}%")
                        ->orWhereRelation('keluarga','no_kk', 'like', "%{$value}%");
                    });
                }),
            ])->allowedFields('penduduk.nik', 'penduduk.nama', 'keluarga.no_kk')
            ->allowedIncludes([
                'keluarga',
                'rtm',
                'penduduk',
                'config',
                'listAnggota',
                AllowedInclude::count('anggota'),
            ])
            ->allowedSorts(['id', 'rtm_id', 'keluarga_id', 'anggota_id', 'config_id'])
            ->jsonPaginate();
    }
}
