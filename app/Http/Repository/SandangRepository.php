<?php

namespace App\Http\Repository;

use App\Models\BantuanPeserta;
use App\Models\Rtm;
use App\Models\Sandang;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SandangRepository
{
    public function listSandang()
    {
        return QueryBuilder::for(Sandang::filterWilayah())
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('id_rtm'),
                AllowedFilter::exact('config_id'),
                AllowedFilter::exact('id_keluarga'),
                AllowedFilter::exact('id_anggota'),
                AllowedFilter::exact('status_pengisian'),
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
                AllowedFilter::callback('penduduk', function ($query, $value) {
                    $query->where('nama', $value);
                }),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('status_pengisian', 'like', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts(['id', 'id_rtm', 'id_keluarga', 'id_anggota', 'tanggal_pengisian'])
            ->jsonPaginate();
    }

    public function listRtm()
    {
        return QueryBuilder::for(Rtm::filterWilayah())
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('nik_kepala'),
                AllowedFilter::exact('no_kk'),
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
                        $query->whereRelation('penduduk', 'nik','like', "%{$value}%")->orWhereRelation('penduduk', 'nama','like', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts(['id', 'nik_kepala', 'no_kk'])
            ->jsonPaginate();
    }
}
