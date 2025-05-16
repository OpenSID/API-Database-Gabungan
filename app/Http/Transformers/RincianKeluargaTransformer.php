<?php

namespace App\Http\Transformers;

use App\Models\Config;
use App\Models\Enums\JenisKelaminEnum;
use App\Models\Keluarga;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class RincianKeluargaTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Keluarga $keluarga)
    {
        $identitas = Config::where('id', $keluarga->config_id)->first();

        return [
            'no_kk' => $keluarga?->no_kk ?? '-',
            'nik_kepala' => $keluarga?->kepalaKeluarga?->nik ?? '-',
            'id_kepala' => $keluarga?->kepalaKeluarga?->id ?? '-',
            'tag_id_card' => $keluarga?->kepalaKeluarga?->tag_id_card ?? '-',
            'sex' => JenisKelaminEnum::getLabel((int) $keluarga?->kepalaKeluarga?->sex),
            'nama_kk' => $keluarga?->kepalaKeluarga?->nama ?? '-',
            'foto' => $keluarga?->kepalaKeluarga?->foto ?? null,
            'tgl_daftar' => $keluarga?->tgl_daftar ?? '-',
            'tgl_cetak_kk' => $keluarga?->tgl_cetak_kk ?? '-',
            'id' => $keluarga?->id ?? '-',
            'alamat_plus_dusun' => ($keluarga?->wilayah?->dusun != '' && $keluarga?->wilayah?->dusun != '-') ?
                trim(ucwords(setting($keluarga?->config_id, 'sebutan_dusun').' '.$keluarga?->wilayah?->dusun)) :
                $keluarga?->wilayah?->dusun ?? '-',
            'rt' => $keluarga?->wilayah?->rt ?? '-',
            'rw' => $keluarga?->wilayah?->rw ?? '-',
            'alamat' => $keluarga?->alamat ?? '-',
            'dusun' => $keluarga?->wilayah?->dusun ?? '-',
            'desa' => ucwords(setting($keluarga?->config_id, 'sebutan_desa').' '.$identitas?->nama_desa ?? '-'),
            'kecamatan' => $identitas?->nama_kecamatan ?? '-',
            'kabupaten' => $identitas?->nama_kabupaten ?? '-',
            'provinsi' => $identitas?->nama_propinsi ?? '-',
            'kode_pos' => $identitas?->kode_pos ?? '-',
            'anggota' => $keluarga?->anggota ?? [],
            'jumlah_anggota' => $keluarga?->anggota?->count() ?? 0,
            'tgl_terdaftar' => $keluarga?->tgl_daftar ? Carbon::createFromFormat('Y-m-d H:i:s', $keluarga?->tgl_daftar)->format('d-m-Y') : '-',
        ];

    }
}
