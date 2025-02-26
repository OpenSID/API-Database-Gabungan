<?php

namespace App\Http\Repository;

use App\Models\Papan;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PapanPresisiRepository
{
    public function index()
    {
        return QueryBuilder::for(Papan::class)
            ->with([
                'rtm',
                'rtm.kepalaKeluarga' => static function ($builder): void {
                    // override all items within the $with property in Penduduk
                    $builder->withOnly('keluarga');
                },                
            ])
            ->allowedFilters([
                AllowedFilter::exact('config_id'),
                AllowedFilter::exact('rtm.kepalaKeluarga.nik'),
                AllowedFilter::callback('kode_kecamatan', function ($query, $value) {
                    $query->whereHas('desa', static fn ($query) => $query->where('kode_kecamatan', $value));
                }),
                AllowedFilter::callback('search', static function ($query, $value) {
                    $query->whereRelation('rtm.kepalaKeluarga', 'nik', 'like', "%{$value}%");
                }),
            ])
            ->jsonPaginate();
    }
}
