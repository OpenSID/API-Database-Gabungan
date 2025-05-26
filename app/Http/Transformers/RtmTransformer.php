<?php

namespace App\Http\Transformers;

use App\Models\Rtm;
use League\Fractal\TransformerAbstract;

class RtmTransformer extends TransformerAbstract
{
    public function transform(Rtm $item)
    {
        return [
            'id' => $item->id,
            'nik_kepala' => $item->nik_kepala,
            'config_id' => $item->config_id,
            'nik' => optional($item->kepalaKeluarga)->nik ?? 'TIDAK TAHU',
            'sex' => optional($item->kepalaKeluarga->jenisKelamin)->nama ?? 'TIDAK TAHU',
            'no_kk' => $item->no_kk,
            'kepala_keluarga' => optional($item->kepalaKeluarga)->nama ?? 'TIDAK TAHU',
            'dtks' => $item->terdaftar_dtks ? 'Terdaftar' : 'Tidak Terdaftar',
            'jumlah_anggota' => $item->anggota->count(),
            'jumlah_kk' => $item->jumlah_kk,
            'alamat' => $item->kepalaKeluarga->alamat_wilayah,
            'dusun' => $item->kepalaKeluarga->keluarga->wilayah->dusun ?? 'TIDAK TAHU',
            'rw' => $item->kepalaKeluarga->keluarga->wilayah->rw ?? 'TIDAK TAHU',
            'rt' => $item->kepalaKeluarga->keluarga->wilayah->rt ?? 'TIDAK TAHU',
            'tgl_daftar' => $item->tgl_daftar ?? 'TIDAK TAHU',
        ];

    }
}
