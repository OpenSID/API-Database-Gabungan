<?php

namespace App\Http\Transformers;

use App\Models\DataPresisiSeniBudaya;
use League\Fractal\TransformerAbstract;

class DataPresisiSeniBudayaTransformer extends TransformerAbstract
{
    public function transform(DataPresisiSeniBudaya $item)
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
            'jenis_seni_yang_dikuasai' => filled($item->jenis_seni_yang_dikuasai) ? $item->jenis_seni_yang_dikuasai : 'TIDAK TAHU',
            'jenis_seni_value' => filled($item->jenis_seni_yang_dikuasai) ? $item->jenis_seni_yang_dikuasai['jenis_seni_value'] : 'TIDAK TAHU',
            'sub_jenis_seni' => filled($item->jenis_seni_yang_dikuasai) ? $item->jenis_seni_yang_dikuasai['sub_jenis_seni'] : 'TIDAK TAHU',
            'jumlah_penghasilan_dari_seni' => filled($item->jumlah_penghasilan_dari_seni) ? $item->jumlah_penghasilan_dari_seni : 'TIDAK TAHU',
            'tanggal_pengisian' => filled($item->tanggal_pengisian) ? $item->tanggal_pengisian : 'TIDAK TAHU',
            'status_pengisian' => filled($item->status_pengisian) ? $item->status_pengisian : 'TIDAK TAHU',
        ];
        
    }
}
