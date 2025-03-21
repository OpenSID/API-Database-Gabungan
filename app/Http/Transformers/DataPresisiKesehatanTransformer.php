<?php

namespace App\Http\Transformers;

use App\Models\Config;
use App\Models\DataPresisiKesehatan;
use League\Fractal\TransformerAbstract;

class DataPresisiKesehatanTransformer extends TransformerAbstract
{
    public function transform(DataPresisiKesehatan $item)
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
            'jns_ansuransi' => filled($item->jns_ansuransi) ? $item->jns_ansuransi : 'TIDAK TAHU',
            'jns_penggunaan_alat_kontrasepsi' => filled($item->jns_penggunaan_alat_kontrasepsi) ? $item->jns_penggunaan_alat_kontrasepsi : 'TIDAK TAHU',
            'jns_penyakit_diderita' => filled($item->jns_penyakit_diderita) ? $item->jns_penyakit_diderita : 'TIDAK TAHU',
            'frekwensi_kunjungan_faskes_pertahun' => filled($item->frekwensi_kunjungan_faskes_pertahun) ? $item->frekwensi_kunjungan_faskes_pertahun : 'TIDAK TAHU',
            'frekwensi_rawat_inap_pertahun' => filled($item->frekwensi_rawat_inap_pertahun) ? $item->frekwensi_rawat_inap_pertahun : 'TIDAK TAHU',
            'frekwensi_kunjungan_dokter_pertahun' => filled($item->frekwensi_kunjungan_dokter_pertahun) ? $item->frekwensi_kunjungan_dokter_pertahun : 'TIDAK TAHU',
            'kondisi_fisik_sejak_lahir' => filled($item->kondisi_fisik_sejak_lahir) ? $item->kondisi_fisik_sejak_lahir : 'TIDAK TAHU',
            'status_gizi_balita' => filled($item->status_gizi_balita) ? $item->status_gizi_balita : 'TIDAK TAHU',
            'tanggal_pengisian' => filled($item->tanggal_pengisian) ? $item->tanggal_pengisian : 'TIDAK TAHU',
            'status_pengisian' => filled($item->status_pengisian) ? $item->status_pengisian : 'TIDAK TAHU',
        ];
        
    }
}
