<?php

namespace App\Http\Repository;

use App\Models\Point;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PointRepository
{
    public function listPoint()
    {
        return  QueryBuilder::for(
                Point::query()
                    ->root() // panggil scope root di sini
            )
            ->with('children') // eager load relasi
            ->where('sumber', 'OpenKab')
            ->root()
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('tipe'),
                AllowedFilter::exact('parent'),
                AllowedFilter::callback('status', function ($query, $value) {
                    $query->where('enabled', 'LIKE', '%'.$value.'%');
                }),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where('nama', 'LIKE', '%'.$value.'%');
                }),

            ])
            ->allowedSorts([
                'nama',
            ])->jsonPaginate();
    }

    public function listSubPoint($id)
    {
        return  QueryBuilder::for(Point::class)
            ->where('sumber', 'OpenKab')
            ->subPoint()
            ->child($id)
            ->allowedFields('*')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::callback('status', function ($query, $value) {
                    $query->where('enabled', 'LIKE', '%'.$value.'%');
                }),
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where('nama', 'LIKE', '%'.$value.'%');
                }),

            ])
            ->allowedIncludes([
                'children',
            ])
            ->allowedSorts([
                'nama',
            ])->jsonPaginate();
    }
}
