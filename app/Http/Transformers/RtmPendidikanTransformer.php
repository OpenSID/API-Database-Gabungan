<?php

namespace App\Http\Transformers;

use App\Models\Config;
use App\Models\Rtm;
use League\Fractal\TransformerAbstract;

class RtmPendidikanTransformer extends TransformerAbstract
{
    public function transform(Rtm $item)
    {
        $statuses = optional($item->dataPresisiKesehatans)->pluck('status_pengisian') ?? collect();
        $status = $statuses->every(fn ($s) => $s === 'lengkap') ? 'Sudah Diisi' : 'Belum Diisi';

        $tanggal = optional($item->dataPresisiKesehatan)->tanggal_pengisian;

        return [
            'id' => $item->id,
            'config_id' => $item->config_id,
            'nik' => optional($item->kepalaKeluarga)->nik ?? 'TIDAK TAHU',
            'no_kk' => $item->no_kk,
            'kepala_keluarga' => optional($item->kepalaKeluarga)->nama ?? 'TIDAK TAHU',
            'dtks' => $item->terdaftar_dtks ? 'Terdaftar' : 'Tidak Terdaftar',
            'jumlah_anggota' => $item->anggota->count(),
            'pendidikan_dalam_kk' => $item->ho_anggota?->dataPresisiPendidikan?->pendidikan_dalam_kk ?? 'TIDAK TAHU',
            'pendidikan_sedang_ditempuh' => $item->ho_anggota?->dataPresisiPendidikan?->pendidikan_sedang_ditempuh ?? 'TIDAK TAHU',
            'keikutsertaan_kip' => $item->ho_anggota?->dataPresisiPendidikan?->keikutsertaan_kip ?? 'TIDAK TAHU',
            'jenis_pendidikan_kesetaraan_yg_diikuti' => $item->ho_anggota?->dataPresisiPendidikan?->jenis_pendidikan_kesetaraan_yg_diikuti ?? 'TIDAK TAHU',
            'jumlah_kk' => $item->jumlah_kk,
            'alamat' => $item->kepalaKeluarga->alamat_wilayah,
            'dusun' => $item->kepalaKeluarga->keluarga->wilayah->dusun,
            'rw' => $item->kepalaKeluarga->keluarga->wilayah->rw,
            'rt' => $item->kepalaKeluarga->keluarga->wilayah->rt,
            'status' => $status,
            'status_html' => '<span class="label label-' . ($status === 'Sudah Diisi' ? 'primary' : 'danger') . '">' . $status . '</span>',
            'tanggal_pengisian' => $tanggal ? date('d-m-Y', strtotime($tanggal)) : '-',
            'tgl_daftar' => $item->tgl_daftar ?? 'TIDAK TAHU',
        ];

    }
}
