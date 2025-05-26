<?php

namespace App\Models;

class SasaranPaud extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sasaran_paud';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    public function scopeFilter($query, array $filters)
    {
        if (! empty($filters['bulan'])) {
            $query->whereMonth('sasaran_paud.created_at', $filters['bulan']);
        }

        if (! empty($filters['tahun'])) {
            $query->whereYear('sasaran_paud.created_at', $filters['tahun']);
        }

        if (! empty($filters['posyandu'])) {
            $query->where('posyandu_id', $filters['posyandu']);
        }

        $filterDesa = $filterKecamatan = $filterKabupaten = false;
        if(! empty($filters['desa'])) {
            $filterDesa = true;
            $filterKabupaten = true;
            $filterKecamatan = true;
        }
        if(! empty($filters['kode_kecamatan'])) {
            $filterKecamatan = true;
            $filterKabupaten = true;
        }
        if(! empty($filters['kabupaten'])) {
            $filterKabupaten = true;
        }
        if($filterDesa){
            $query->whereRelation('desa', static function ($query) use ($filters) {
                $query->where('kode_desa', $filters['desa']);
            });
        }else {
            if($filterKecamatan) {
                $query->whereRelation('desa', static function ($query) use ($filters) {
                    $query->where('kode_kecamatan', $filters['kode_kecamatan']);
                });
            }else {
                if($filterKabupaten) {
                    $query->whereRelation('desa', static function ($query) use ($filters) {
                        $query->where('kode_kabupaten', $filters['kabupaten']);
                    });
                }
            }
        }

        return $query;
    }
}
