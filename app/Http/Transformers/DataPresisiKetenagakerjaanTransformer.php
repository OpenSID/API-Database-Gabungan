<?php

namespace App\Http\Transformers;

use App\Models\DataPresisiKetenagakerjaan;
use League\Fractal\TransformerAbstract;

class DataPresisiKetenagakerjaanTransformer extends TransformerAbstract
{
    public function transform(DataPresisiKetenagakerjaan $item)
    {
        return [
            'id' => $item->uuid,
            'config_id' => $item->config_id,
            'nik' => optional($item->penduduk)->nik ?? 'TIDAK TAHU',
            'no_kk' => optional($item->rtm)->no_kk ?? 'TIDAK TAHU',
            'nama' => optional($item->penduduk)->nama ?? 'TIDAK TAHU',
            'rtm_id' => $item->rtm_id ?? 'TIDAK TAHU',
            'keluarga_id' => $item->keluarga_id ?? 'TIDAK TAHU',
            'anggota_id' => $item->anggota_id ?? 'TIDAK TAHU',
            'jenis_pekerjaan' => filled($item->jenis_pekerjaan) ? $item->jenis_pekerjaan : 'TIDAK TAHU',
            'tempat_kerja' => filled($item->tempat_kerja) ? $item->tempat_kerja : 'TIDAK TAHU',
            'frekwensi_mengikuti_pelatihan_setahun' => filled($item->frekwensi_mengikuti_pelatihan_setahun) ? $item->frekwensi_mengikuti_pelatihan_setahun : 'TIDAK TAHU',
            'jenis_pelatihan_diikuti_setahun' => filled($item->jenis_pelatihan_diikuti_setahun) ? $item->jenis_pelatihan_diikuti_setahun : 'TIDAK TAHU',
            'tanggal_pengisian' => filled($item->tanggal_pengisian) ? $item->tanggal_pengisian : 'TIDAK TAHU',
            'status_pengisian' => filled($item->status_pengisian) ? $item->status_pengisian : 'TIDAK TAHU',
        ];
        
    }
}
