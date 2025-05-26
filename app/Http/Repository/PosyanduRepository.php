<?php

namespace App\Http\Repository;

use App\Models\Posyandu;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PosyanduRepository
{
    public function listPosyandu()
    {
        return QueryBuilder::for(Posyandu::class)
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('config_id'),
                AllowedFilter::callback('kode_kecamatan', function ($query, $value) {
                    $query->whereHas('config', static fn ($query) => $query->where('kode_kecamatan', $value));
                }),
                AllowedFilter::callback('kode_desa', function ($query, $value) {
                    $query->whereHas('config', function ($query) use ($value) {
                        $query->where('kode_desa', $value);
                    });
                }),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($query) use ($value) {
                        $query->where('nama', 'like', "%{$value}%")
                        ->orWhere('alamat','like', "%{$value}%");
                    });
                }),
            ])
            ->allowedSorts([
                'nama',
                'alamat',
                'created_at',
            ])
            ->jsonPaginate();
    }

}
