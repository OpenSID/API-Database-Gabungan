<?php

namespace App\Http\Repository;

use App\Models\DataPresisiAdat;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

class DataPresisiAdatRepository
{
    public function index()
    {
        return QueryBuilder::for(DataPresisiAdat::tahunAktif())
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
                        $query->where('penduduk.nama', 'like', "%{$value}%")
                        ->orWhere('penduduk.nik', 'like', "%{$value}%")
                        ->orWhere('keluarga.no_kk', 'like', "%{$value}%");
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
