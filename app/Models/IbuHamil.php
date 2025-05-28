<?php

namespace App\Models;

class IbuHamil extends BaseModel
{
    /**
     * Static data status kehamilan ibu.
     *
     * @var array
     */
    public const STATUS_KEHAMILAN_IBU = [
        [
            'id' => 1,
            'simbol' => 'N',
            'nama' => 'Normal (N)',
        ],
        [
            'id' => 2,
            'simbol' => 'Risti',
            'nama' => 'Risiko Tinggi (Risti)',
        ],
        [
            'id' => 3,
            'simbol' => 'KEK',
            'nama' => 'Kekurangan Energi Kronis (KEK)',
        ],
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ibu_hamil';

    /**
     * The table update parameter.
     *
     * @var string
     */
    public $primaryKey = 'id_ibu_hamil';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    public function kia()
    {
        return $this->belongsTo(KIA::class, 'kia_id');
    }

    public function scopeFilter($query, array $filters)
    {
        if (! empty($filters['bulan'])) {
            $query->whereMonth('ibu_hamil.created_at', $filters['bulan']);
        }

        if (! empty($filters['tahun'])) {
            $query->whereYear('ibu_hamil.created_at', $filters['tahun']);
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
