<?php

namespace App\Http\Transformers;

use App\Models\Config;
use App\Models\Rtm;
use League\Fractal\TransformerAbstract;

class RtmTransformer extends TransformerAbstract
{
    public function transform(Rtm $item)
    {
        return [
            'id' => $item->id,
            'config_id' => $item->config_id,
            'nik' => optional($item->kepalaKeluarga)->nik ?? 'TIDAK TAHU',
            'no_kk' => $item->no_kk,
            'kepala_keluarga' => optional($item->kepalaKeluarga)->nama ?? 'TIDAK TAHU',
            'dtks' => $item->terdaftar_dtks ? 'Terdaftar' : 'Tidak Terdaftar',
            'jumlah_anggota' => $item->anggota->count(),
            'jumlah_kk' => $item->jumlah_kk,
            'alamat' => $item->kepalaKeluarga->alamat_wilayah,
            'dusun' => $item->kepalaKeluarga->keluarga->wilayah->dusun,
            'rw' => $item->kepalaKeluarga->keluarga->wilayah->rw,
            'rt' => $item->kepalaKeluarga->keluarga->wilayah->rt,
            'tgl_daftar' => $item->tgl_daftar ?? 'TIDAK TAHU',
        ];
        
    }
}
