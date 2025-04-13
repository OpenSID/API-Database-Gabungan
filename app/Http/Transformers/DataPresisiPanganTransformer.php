<?php

namespace App\Http\Transformers;

use App\Models\DataPresisiPangan;
use League\Fractal\TransformerAbstract;

class DataPresisiPanganTransformer extends TransformerAbstract
{
    public function transform(DataPresisiPangan $item)
    {
        return [
            'id' => $item->uuid,
            'config_id' => $item->config_id,
            'nik' => optional($item->rtm->kepalaKeluarga)->nik ?? 'TIDAK TAHU',
            'no_kk' => optional($item->rtm)->no_kk ?? 'TIDAK TAHU',
            'nama' => optional($item->rtm->kepalaKeluarga)->nama ?? 'TIDAK TAHU',
            'rtm_id' => $item->rtm_id ?? 'TIDAK TAHU',
            'keluarga_id' => $item->keluarga_id ?? 'TIDAK TAHU',
            'jenis_lahan' => filled($item->jenis_lahan) ? $item->jenis_lahan : 'TIDAK TAHU',
            'luas_lahan' => filled($item->luas_lahan) ? $item->luas_lahan : 'TIDAK TAHU',
            'luas_tanam' => filled($item->luas_tanam) ? $item->luas_tanam : 'TIDAK TAHU',
            'status_lahan' => filled($item->status_lahan) ? $item->status_lahan : 'TIDAK TAHU',
            'komoditi_utama_tanaman_pangan' => filled($item->komoditi_utama_tanaman_pangan) ? $item->komoditi_utama_tanaman_pangan : 'TIDAK TAHU',
            'komoditi_tanaman_pangan_lainnya' => filled($item->komoditi_tanaman_pangan_lainnya) ? $item->komoditi_tanaman_pangan_lainnya : 'TIDAK TAHU',
            'jumlah_berdasarkan_jenis_komoditi' => filled($item->jumlah_berdasarkan_jenis_komoditi) ? $item->jumlah_berdasarkan_jenis_komoditi : 'TIDAK TAHU',
            'usia_komoditi' => filled($item->usia_komoditi) ? $item->usia_komoditi : 'TIDAK TAHU',
            'jenis_peternakan' => filled($item->jenis_peternakan) ? $item->jenis_peternakan : 'TIDAK TAHU',
            'jumlah_populasi' => filled($item->jumlah_populasi) ? $item->jumlah_populasi : 'TIDAK TAHU',
            'jenis_perikanan' => filled($item->jenis_perikanan) ? $item->jenis_perikanan : 'TIDAK TAHU',
            'frekwensi_makanan_perhari' => filled($item->frekwensi_makanan_perhari) ? $item->frekwensi_makanan_perhari : 'TIDAK TAHU',
            'frekwensi_konsumsi_sayur_perhari' => filled($item->frekwensi_konsumsi_sayur_perhari) ? $item->frekwensi_konsumsi_sayur_perhari : 'TIDAK TAHU',
            'frekwensi_konsumsi_buah_perhari' => filled($item->frekwensi_konsumsi_buah_perhari) ? $item->frekwensi_konsumsi_buah_perhari : 'TIDAK TAHU',
            'frekwensi_konsumsi_daging_perhari' => filled($item->frekwensi_konsumsi_daging_perhari) ? $item->frekwensi_konsumsi_daging_perhari : 'TIDAK TAHU',
            'tanggal_pengisian' => filled($item->tanggal_pengisian) ? $item->tanggal_pengisian : 'TIDAK TAHU',
            'status_pengisian' => filled($item->status_pengisian) ? $item->status_pengisian : 'TIDAK TAHU',
        ];
        
    }
}
