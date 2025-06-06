<?php

namespace App\Models;

use App\Models\Traits\ConfigIdTrait;


class Covid extends BaseModel
{
    use ConfigIdTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ref_status_covid';

    /**
     * Scope untuk Statistik.
     */
    public function scopeCountStatistik($query)
    {
        return $query
            ->select(['ref_status_covid.id', 'ref_status_covid.nama'])
            ->selectRaw('COUNT(CASE WHEN tweb_penduduk.sex = 1 THEN tweb_penduduk.id END) AS laki_laki')
            ->selectRaw('COUNT(CASE WHEN tweb_penduduk.sex = 2 THEN tweb_penduduk.id END) AS perempuan')
            ->selectRaw("concat('{\"status_covid\":\"',ref_status_covid.id,'\"}') as kriteria")
            ->join('covid19_pemudik', 'covid19_pemudik.status_covid', '=', 'ref_status_covid.id')
            ->join('tweb_penduduk', 'tweb_penduduk.id', '=', 'covid19_pemudik.id_terdata')
            ->where('tweb_penduduk.status_dasar', 1)
            ->groupBy(['ref_status_covid.id', 'ref_status_covid.nama']);
    }
}
