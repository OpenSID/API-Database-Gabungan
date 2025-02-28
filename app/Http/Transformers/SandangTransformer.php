<?php

namespace App\Http\Transformers;

use App\Models\Config;
use App\Models\Sandang;
use League\Fractal\TransformerAbstract;

class SandangTransformer extends TransformerAbstract
{
    public function transform(Sandang $item)
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
            'jml_pakaian_yg_dimiliki' => filled($item->jml_pakaian_yg_dimiliki) ? $item->jml_pakaian_yg_dimiliki : 'TIDAK TAHU',
            'frekwensi_beli_pakaian_pertahun' => filled($item->frekwensi_beli_pakaian_pertahun) ? $item->frekwensi_beli_pakaian_pertahun : 'TIDAK TAHU',
            'jenis_pakaian' => filled($item->jenis_pakaian) ? $item->jenis_pakaian : 'TIDAK TAHU',
            'frekwensi_ganti_pakaian' => filled($item->frekwensi_ganti_pakaian) ? $item->frekwensi_ganti_pakaian : 'TIDAK TAHU',
            'tmpt_cuci_pakaian' => filled($item->tmpt_cuci_pakaian) ? $item->tmpt_cuci_pakaian : 'TIDAK TAHU',
            'jml_pakaian_seragam' => filled($item->jml_pakaian_seragam) ? $item->jml_pakaian_seragam : 'TIDAK TAHU',
            'jml_pakaian_sembahyang' => filled($item->jml_pakaian_sembahyang) ? $item->jml_pakaian_sembahyang : 'TIDAK TAHU',
            'jml_pakaian_kerja' => filled($item->jml_pakaian_kerja) ? $item->jml_pakaian_kerja : 'TIDAK TAHU',
            'tanggal_pengisian' => filled($item->tanggal_pengisian) ? $item->tanggal_pengisian : 'TIDAK TAHU',
            'status_pengisian' => filled($item->status_pengisian) ? $item->status_pengisian : 'TIDAK TAHU',
        ];
        
    }
}
