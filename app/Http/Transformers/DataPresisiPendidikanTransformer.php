<?php

namespace App\Http\Transformers;

use App\Models\DataPresisiPendidikan;
use League\Fractal\TransformerAbstract;

class DataPresisiPendidikanTransformer extends TransformerAbstract
{
    public function transform(DataPresisiPendidikan $item)
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
            'pendidikan_dalam_kk' => filled($item->pendidikan_dalam_kk) ? $item->pendidikan_dalam_kk : 'TIDAK TAHU',
            'pendidikan_sedang_ditempuh' => filled($item->pendidikan_sedang_ditempuh) ? $item->pendidikan_sedang_ditempuh : 'TIDAK TAHU',
            'keikutsertaan_kip' => filled($item->keikutsertaan_kip) ? $item->keikutsertaan_kip : 'TIDAK TAHU',
            'jenis_pendidikan_kesetaraan_yg_diikuti' => filled($item->jenis_pendidikan_kesetaraan_yg_diikuti) ? $item->jenis_pendidikan_kesetaraan_yg_diikuti : 'TIDAK TAHU',
            'tanggal_pengisian' => filled($item->tanggal_pengisian) ? $item->tanggal_pengisian : 'TIDAK TAHU',
            'status_pengisian' => filled($item->status_pengisian) ? $item->status_pengisian : 'TIDAK TAHU',
        ];
        
    }
}
